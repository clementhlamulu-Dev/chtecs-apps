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
						<a href="video-quiz.php" id="proceedBtn" class="submit-btn disabled">
							Proceed
						</a>
					</div>

					<script>

						const video = document.getElementById("html5_video_7pxmfc8xc5b");
						const proceedBtn = document.getElementById("proceedBtn");

						// -----------------------------
						// VIDEO COMPLETION
						// -----------------------------

						let unlocked = false;

						video.addEventListener("timeupdate", function () {

							if (unlocked || !video.duration) return;

							const percentage = (video.currentTime / video.duration) * 100;

							if (percentage >= 80) {
								unlocked = true;
								proceedBtn.classList.remove("disabled");
							}
						});


						// -----------------------------
						// WATCH TIME TRACKING
						// -----------------------------

						let lastVideoTime = 0;
						let accumulatedWatchTime = 0;


						// Check video position every second
						setInterval(function () {

							// Video is playing
							if (!video.paused && !video.ended && video.readyState >= 2) {

								const currentVideoTime = video.currentTime;

								/*
								 * Calculate how much the video actually moved.
								 */
								const difference = currentVideoTime - lastVideoTime;

								/*
								 * Only count normal playback.
						
								 * If someone seeks forward 5 minutes,
								 * we don't want to count those 5 minutes
								 * as watched.
								 */
								if (difference > 0 && difference <= 2) {
									accumulatedWatchTime += difference;
								}

								lastVideoTime = currentVideoTime;
							}

						}, 1000);


						// -----------------------------
						// SEND WATCH TIME EVERY 30 SEC
						// -----------------------------

						setInterval(function () {

							if (accumulatedWatchTime >= 30) {

								const secondsToSave = Math.floor(accumulatedWatchTime);

								saveWatchTime(secondsToSave);

								// Reset after sending
								accumulatedWatchTime = 0;
							}

						}, 30000);


						// -----------------------------
						// SAVE TO DATABASE
						// -----------------------------

						function saveWatchTime(seconds) {

							const formData = new FormData();

							formData.append("video_id", "IT_VIDEO_001");
							formData.append("watch_time", seconds);

							fetch("save-video-progress.php", {
								method: "POST",
								body: formData
							})
								.then(response => response.json())
								.then(data => {

									if (data.success) {
										console.log("Watch time saved:", seconds, "seconds");
									} else {
										console.error("Failed to save watch time:", data.message);
									}

								})
								.catch(error => {
									console.error("Error saving watch time:", error);
								});

						}

					</script>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>