<?php


$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}
?>

<!DOCTYPE html>
<html lang="eng">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Company Name Results type for the year ended Day&nbsp;Month&nbsp;Year |
		<?php echo $pageTitle ?>
	</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>

	<link rel="stylesheet" href="css/app-styles.css">
</head>


<body id="<SECTIONSHORT>">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<?php include('includes/report-tools.php'); ?>

	<div id="selectable-content">
		<div class="container">

			<div class="induction-video">
				<div class="row">
					<div class="col-lg-12">

						<h2 class="switch-red">Business: <?php echo "Information Technology" ?></h2>
						<p>please watch the entire video before proceeding</p>


						<video controls="controls" class="video"
							poster="https://vod.overendstudio.co.za/uploadimages/Screenshot_20260601_122755_copy_thumbnail_1780320029.jpg"
							preload="none" width="100%" height="auto" id="html5_video_7pxmfc8xc5b">
							<!-- MP4 must be first for iPad! -->

							<source src="" type="video/mp4">
							<!-- Safari / iOS video    -->

							<source src="https://dev-apps.chtecs.co.za/videos/worksafe-induction-video.mp4"
								type="video/mp4">
							<!-- fallback to Flash: -->
							<object width="650" height="390" type="application/x-shockwave-flash" data="player.swf"
								class="skrollable skrollable-between">
								<!-- Firefox uses the `data` attribute above, IE/Safari uses the param below -->
								<param name="movie" value="player.swf">
								<param name="allowfullscreen" value="true">
								<param name="wmode" value="transparent">
								<param name="allowScriptAccess" value="never">
								<param name="flashvars"
									value="controlbar=over&amp;image=https://www.bastiongroup.co.za/video/uploadimages/Screenshot_20260601_122755_copy_thumbnail.jpg&amp;file=/video/video/Cell_C_BTH_lores_v3.mp4">
								<!-- fallback image. note the title field below, put the title of the video there -->

							</object>
						</video>

						<!-- the system records that the video has been watched. -->



					</div>

					<div class="col-lg-12 ">
						<a href="video-quiz.php?id=<?php echo $user_id; ?>" id="proceedBtn" class="submit-btn disabled">
							Proceed
						</a>
					</div>

					<script>

						const video = document.getElementById("html5_video_7pxmfc8xc5b");
						const proceedBtn = document.getElementById("proceedBtn");

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

						function checkUnlock() {
							if (!unlocked && video.duration && totalWatched >= video.duration * UNLOCK_RATIO) {
								unlocked = true;
								proceedBtn.classList.remove("disabled");
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
							formData.append("duration", video.duration.toFixed(2));   // ← was missing

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
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>