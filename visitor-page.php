<?php


$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}
$pageTitle = "Induction Video";
$isVisitorPage = true;
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


<body id="visitor-page">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>

	<div id="selectable-content">
		<div class="container">

			<ol class="steps" aria-label="Induction steps">
				<li class="current"><span class="step-num">1</span> Watch video</li>
				<li><span class="step-num">2</span> Assessment</li>
				<li><span class="step-num">3</span> Results</li>
			</ol>

			<div class="induction-video">
				<div class="page-head">
					<span class="eyebrow">Business: <?php echo "Information Technology" ?></span>
					<h1 class="switch-dblue">Health &amp; Safety Induction</h1>
					<p class="page-intro">Please watch the video before starting the short assessment.
						Your progress is saved automatically, so you can pause and come back later.</p>
				</div>

				<div class="app-card">
					<div class="video-frame">
						<video controls="controls" class="video" playsinline
							poster="https://vod.overendstudio.co.za/uploadimages/Screenshot_20260601_122755_copy_thumbnail_1780320029.jpg"
							preload="none" width="100%" height="auto" id="html5_video_7pxmfc8xc5b">
							<source src="https://dev-apps.chtecs.co.za/videos/worksafe-induction-video.mp4"
								type="video/mp4">
							Your browser does not support HTML5 video.
						</video>
					</div>

					<!-- the system records that the video has been watched. -->
					<div class="watch-status">
						<div class="meter">
							<div class="meter-label">
								<span>Video watched</span>
								<strong id="watchPercent">0%</strong>
							</div>
							<div class="progress-container" role="progressbar" aria-labelledby="watchPercent"
								aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="watchMeter">
								<div class="progress-bar" id="watchBar" style="width: 0%;"></div>
							</div>
							<p class="watch-hint" id="watchHint" aria-live="polite">
								Watch at least 80% of the video to unlock the assessment.
							</p>
						</div>

						<a href="video-quiz.php?id=<?php echo $user_id; ?>" id="proceedBtn" class="submit-btn disabled"
							aria-disabled="true">
							Start Assessment &rarr;
						</a>
					</div>
				</div>

				<script>

					const video = document.getElementById("html5_video_7pxmfc8xc5b");
					const proceedBtn = document.getElementById("proceedBtn");
					const watchBar = document.getElementById("watchBar");
					const watchMeter = document.getElementById("watchMeter");
					const watchPercent = document.getElementById("watchPercent");
					const watchHint = document.getElementById("watchHint");

					const USER_ID = <?php echo json_encode($user_id); ?>;
					const SAVE_EVERY = 10;
					const UNLOCK_RATIO = 0.8;
					const ENDPOINT = "save-video-progress.php";

					let lastVideoTime = 0;
					let unsaved = 0;
					let totalWatched = 0;
					let unlocked = false;

					// Load previous progress and resume
					video.addEventListener("loadedmetadata", () => {
						fetch(`${ENDPOINT}?id=${encodeURIComponent(USER_ID)}`)
							.then(r => r.json())
							.then(data => {
								if (!data.success) return;
								totalWatched = data.watched;
								if (data.last_position > 0 && data.last_position < video.duration - 2) {
									video.currentTime = data.last_position;
								}
								lastVideoTime = video.currentTime;
								checkUnlock();
							})
							.catch(err => console.error("Could not load progress:", err));
					});

					// Count only normal playback, not skipping
					video.addEventListener("timeupdate", () => {
						const diff = video.currentTime - lastVideoTime;
						if (!video.seeking && diff > 0 && diff <= 1.5) {
							unsaved += diff;
							totalWatched += diff;
						}
						lastVideoTime = video.currentTime;

						if (unsaved >= SAVE_EVERY) flush();
						checkUnlock();
					});

					video.addEventListener("seeked", () => { lastVideoTime = video.currentTime; });

					// Visual feedback only - does not affect what is saved
					function updateMeter() {
						if (!video.duration) return;
						const pct = Math.min(100, Math.round((totalWatched / video.duration) * 100));
						watchBar.style.width = pct + "%";
						watchPercent.textContent = pct + "%";
						watchMeter.setAttribute("aria-valuenow", pct);
					}

					function checkUnlock() {
						updateMeter();
						if (!unlocked && video.duration && totalWatched >= video.duration * UNLOCK_RATIO) {
							unlocked = true;
							proceedBtn.classList.remove("disabled");
							proceedBtn.removeAttribute("aria-disabled");
							watchBar.classList.add("complete");
							watchHint.textContent = "Great - the assessment is now unlocked.";
							watchHint.classList.add("unlocked");
						}
					}

					function flush(useBeacon = false) {
						const seconds = Math.floor(unsaved);
						if (seconds < 1) return;
						unsaved -= seconds;

						const formData = new FormData();
						formData.append("id", USER_ID);
						formData.append("watch_time", seconds);
						formData.append("position", video.currentTime.toFixed(2));
						formData.append("duration", video.duration.toFixed(2));

						if (useBeacon) {
							navigator.sendBeacon(ENDPOINT, formData);
							return;
						}

						fetch(ENDPOINT, { method: "POST", body: formData })
							.then(r => r.json())
							.then(data => {
								if (data.success) console.log("Saved", seconds, "seconds");
								else { console.error("Save failed:", data.message); unsaved += seconds; }
							})
							.catch(err => { console.error("Save error:", err); unsaved += seconds; });
					}

					video.addEventListener("pause", () => flush());
					video.addEventListener("ended", () => flush());
					window.addEventListener("pagehide", () => flush(true));

				</script>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>
