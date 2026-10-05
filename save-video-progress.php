<?php

require_once __DIR__ . "/includes/db-connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$video_id = $_POST['video_id'] ?? '';
$watch_time = isset($_POST['watch_time']) ? (int) $_POST['watch_time'] : 0;

// You should get these from the logged-in/session user
$email = $_SESSION['email'] ?? '';

if (empty($video_id) || empty($email) || $watch_time <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required information'
    ]);
    exit;
}

/*
 * Add watch time to the user's existing watch time.
 */
$sql = "
    UPDATE InductionTable
    SET watch_time_seconds = watch_time_seconds + ?
    WHERE email = ?
    AND video_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iss",
    $watch_time,
    $email,
    $video_id
);

if ($stmt->execute()) {

    echo json_encode([
        'success' => true,
        'message' => 'Watch time saved'
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Database update failed'
    ]);
}

$stmt->close();
$conn->close();