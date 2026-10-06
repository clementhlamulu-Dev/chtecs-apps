<?php
header('Content-Type: application/json');
require_once __DIR__ . "/includes/db-connect.php"; // assumes a mysqli $conn
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function respond($code, $data)
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// The id from the link (sent as ?id= on GET, or in the form data on POST)
$user_id = filter_var($_REQUEST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
    respond(400, ['success' => false, 'message' => 'Missing or invalid id']);
}

// ---------- GET: load saved progress ----------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare(
        "SELECT VideoProgress_seconds, last_position FROM InductionTable WHERE user_id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    respond(200, [
        'success' => true,
        'watched' => (int) ($row['VideoProgress_seconds'] ?? 0),
        'last_position' => (float) ($row['last_position'] ?? 0),
    ]);
}

// ---------- POST: add watched seconds ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $watch_time = (int) ($_POST['watch_time'] ?? 0);
    $position = (float) ($_POST['position'] ?? 0);
    $duration = (float) ($_POST['duration'] ?? 0);

    if ($watch_time <= 0 || $watch_time > 60 || $duration <= 0) {
        respond(400, ['success' => false, 'message' => 'Invalid data']);
    }

    $stmt = $conn->prepare(
        "UPDATE InductionTable
         SET VideoProgress_seconds = VideoProgress_seconds + ?,
             Videoprogress_percent = LEAST(100, (VideoProgress_seconds / ?) * 100),
             last_position = ?
         WHERE user_id = ?"
    );
    $stmt->bind_param("iddi", $watch_time, $duration, $position, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        respond(404, ['success' => false, 'message' => 'Visitor not found']);
    }
    respond(200, ['success' => true]);
}
?>