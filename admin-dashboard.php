<?php
require_once __DIR__ . "/includes/db-connect.php";
$pageTitle = "Admin Dashboard";

function e($value)
{
	return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES);
}

// Delete a visitor, then redirect so a page refresh doesn't resubmit the form.
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
	$deleteId = filter_var($_POST["user_id"] ?? null, FILTER_VALIDATE_INT);
	$status = "delete-failed";

	if ($deleteId) {
		try {
			$stmt = $conn->prepare("DELETE FROM InductionTable WHERE user_id = ?");
			if ($stmt && $stmt->bind_param("i", $deleteId) && $stmt->execute()) {
				$status = $stmt->affected_rows > 0 ? "deleted" : "not-found";
			} else {
				error_log("Deleting visitor $deleteId failed: " . $conn->error);
			}
			if ($stmt) {
				$stmt->close();
			}
		} catch (mysqli_sql_exception $ex) {
			error_log("Deleting visitor $deleteId failed: " . $ex->getMessage());
		}
	}

	header("Location: admin-dashboard.php?status=$status");
	exit;
}

$statusMessages = [
	"deleted" => ["success", "The visitor was deleted."],
	"not-found" => ["warning", "That visitor no longer exists. It may already have been deleted."],
	"delete-failed" => ["error", "The visitor could not be deleted. Please try again."],
];
$status = $statusMessages[$_GET["status"] ?? ""] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $pageTitle ?> | CH Tecs Visitor Induction</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>
</head>


<body id="admin-dashboard">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<?php include('includes/report-tools.php'); ?>
	<div id="selectable-content">
		<div class="container">
			<div class="page-head d-flex flex-wrap justify-content-between align-items-end gap-3">
				<div>
					<span class="eyebrow">Overview</span>
					<h1 class="switch-dblue">Admin Dashboard</h1>
					<p class="page-intro">Track visitor invitations, video progress and assessment results.</p>
				</div>
				<a href="host-form.php" class="submit-btn">+ Invite a Visitor</a>
			</div>

			<?php if ($status): ?>
				<div class="app-alert app-alert-<?php echo $status[0]; ?>" role="status">
					<span><?php echo $status[1]; ?></span>
				</div>
			<?php endif; ?>

			<?php
			$userCount = $WatchVideoCount = $QuizTakersCount = $QuizPassersCount = $QuizFailersCount = 0;

			$sql = "SELECT COUNT(*) AS userCount FROM InductionTable WHERE user_id IS NOT NULL";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$userCount = $row["userCount"];
				}
			}

			$sql = "SELECT COUNT(*) AS WatchVideoCount FROM InductionTable WHERE VideoProgress_seconds != 0";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$WatchVideoCount = $row["WatchVideoCount"];
				}
			}

			$sql = "SELECT COUNT(*) AS QuizTakersCount FROM InductionTable WHERE Question1 IS NOT NULL";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$QuizTakersCount = $row["QuizTakersCount"];
				}
			}

			$sql = "SELECT COUNT(*) AS QuizPassersCount FROM InductionTable WHERE Quiz_Results >=20";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$QuizPassersCount = $row["QuizPassersCount"];
				}
			}

			$sql = "SELECT COUNT(*) AS QuizFailersCount FROM InductionTable WHERE Question1 IS NOT NULL AND Quiz_Results < 20";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$QuizFailersCount = $row["QuizFailersCount"];
				}
			}

			$passRate = $QuizTakersCount > 0 ? round(($QuizPassersCount / $QuizTakersCount) * 100) : 0;
			?>

			<div class="stat-grid">
				<div class="das-summery">
					<div class="dash-number"><?php echo $userCount; ?></div>
					<div class="das-discription">Visitors invited</div>
				</div>
				<div class="das-summery tone-chrome">
					<div class="dash-number"><?php echo $WatchVideoCount; ?></div>
					<div class="das-discription">Started the video</div>
				</div>
				<div class="das-summery">
					<div class="dash-number"><?php echo $QuizTakersCount; ?></div>
					<div class="das-discription">Took the quiz</div>
				</div>
				<div class="das-summery tone-success">
					<div class="dash-number"><?php echo $QuizPassersCount; ?></div>
					<div class="das-discription">Passed the quiz</div>
				</div>
				<div class="das-summery tone-danger">
					<div class="dash-number"><?php echo $QuizFailersCount; ?></div>
					<div class="das-discription">Failed the quiz</div>
				</div>
				<div class="das-summery tone-success">
					<div class="dash-number"><?php echo $passRate; ?>%</div>
					<div class="das-discription">Pass rate</div>
				</div>
			</div>

			<div class="app-card">
				<div class="toolbar">
					<h2>Visitors</h2>
					<div class="search-box">
						<label for="visitorSearch" class="visually-hidden">Search visitors</label>
						<input type="search" id="visitorSearch" class="app-input"
							placeholder="Search by name, email, department or host">
					</div>
				</div>

				<div class="table-wrap">
					<table class="visitor-table unwrap" id="visitorTable">
						<thead>
							<tr>
								<th scope="col">Visitor</th>
								<th scope="col">Department</th>
								<th scope="col">Host</th>
								<th scope="col">Video progress</th>
								<th scope="col">Quiz</th>
								<th scope="col" class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM InductionTable";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									$user_id = (int) $row["user_id"];
									$FullName = $row["firstname"];
									$VisitorEmail = $row["email"];
									$businessUnit = $row["BusinessUnit"];
									$Host = $row["Host"];
									$Videoprogress_percent = (int) $row["Videoprogress_percent"];
									$Quiz_Results = $row["Quiz_Results"];
									$quizTaken = !empty($row["Question1"]);

									$initial = strtoupper(substr($FullName, 0, 1));

									if (!$quizTaken) {
										$badge = "<span class='badge badge-pending'>Not taken</span>";
									} elseif ($Quiz_Results >= 20) {
										$badge = "<span class='badge badge-pass'>Pass</span>";
									} else {
										$badge = "<span class='badge badge-fail'>Fail</span>";
									}
									$barClass = $Videoprogress_percent >= 80 ? 'progress-bar complete' : 'progress-bar';
									?>
									<tr>
										<td>
											<div class="person">
												<span class="initials"><?php echo e($initial); ?></span>
												<div>
													<p class="person-name"><?php echo e($FullName); ?></p>
													<p class="user-email"><?php echo e($VisitorEmail); ?></p>
													<p class="user-email">ID: <?php echo $user_id; ?></p>
												</div>
											</div>
										</td>
										<td><?php echo e($businessUnit); ?></td>
										<td><?php echo e($Host); ?></td>
										<td>
											<p class="user-email mb-1"><?php echo $Videoprogress_percent; ?>%</p>
											<div class="progress-container">
												<div class="<?php echo $barClass; ?>" style="width: <?php echo min(100, $Videoprogress_percent); ?>%;"></div>
											</div>
										</td>
										<td>
											<?php echo $badge; ?>
											<?php if ($quizTaken): ?>
												<p class="user-email mt-1"><?php echo e($Quiz_Results); ?> / 25</p>
											<?php endif; ?>
										</td>
										<td>
											<div class="actions">
												<a href="quiz-results.php?id=<?php echo $user_id; ?>" class="submit-btn btn-outline btn-sm">View Quiz</a>
												<form action="admin-dashboard.php" method="post" class="delete-form"
													data-name="<?php echo e($FullName); ?>">
													<input type="hidden" name="action" value="delete">
													<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
													<button type="submit" class="btn delete-btn">Delete</button>
												</form>
											</div>
										</td>
									</tr>
									<?php
								}
							} else {
								echo "<tr><td colspan='6' class='empty-state'>No visitors yet. <a href='host-form.php'>Send the first invitation</a>.</td></tr>";
							}
							?>
							<tr id="noMatches" style="display:none">
								<td colspan="6" class="empty-state">No visitors match your search.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>

	<script>
		// Ask before deleting - this cannot be undone
		document.querySelectorAll('.delete-form').forEach(function (form) {
			form.addEventListener('submit', function (e) {
				if (!confirm('Delete ' + form.dataset.name + '? Their invitation, video progress and quiz results will be removed permanently.')) {
					e.preventDefault();
				}
			});
		});

		// Simple client-side filter for the visitor table
		document.getElementById('visitorSearch').addEventListener('input', function () {
			var term = this.value.trim().toLowerCase();
			var rows = document.querySelectorAll('#visitorTable tbody tr:not(#noMatches)');
			var shown = 0;
			rows.forEach(function (row) {
				var match = row.textContent.toLowerCase().indexOf(term) !== -1;
				row.style.display = match ? '' : 'none';
				if (match) shown++;
			});
			document.getElementById('noMatches').style.display = (shown === 0 && rows.length > 0) ? '' : 'none';
		});
	</script>
</body>

</html>
