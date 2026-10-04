<?php
include("includes/db-connect.php");
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
	<?php include('includes/breadcrumb.php'); ?>
	<div id="selectable-content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">

					<div class="main-page-heading">
						<h1 class="switch-red">
							Admin Dashboard
						</h1>
					</div>



				</div>



				<div class="dashboard-block">
					<div class="row">
						<div class="col-lg-4">
							<h2 class="dashboard-headline">User Details</h2>
						</div>
						<div class="col-lg-1">
							<h2 class="dashboard-headline">Video ID</h2>
						</div>

						<div class="col-lg-3">
							<h2 class="dashboard-headline">Business Department</h2>
						</div>

						<div class="col-lg-3">
							<h2 class="dashboard-headline">Induction Host</h2>
						</div>
					</div>
				</div>




				<?php
				$sql = "SELECT * FROM InductionTable";
				// Execute the SQL query
				$result = $conn->query($sql);

				// Process the result set
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						// echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
						$user_id = $row["user_id"];
						$video_Id = $row["video_id"];
						$FullName = $row["firstname"];
						$VisitorEmail = $row["email"];
						$businessUnit = $row["BusinessUnit"];
						$Host = $row["Host"];
						$VideoProgress_seconds = $row["VideoProgress_seconds"];
						$Videoprogress_percent = $row["Videoprogress_percent"];
						//	$Question1 = $row[" Question1"];
						$Question2 = $row["Question2"];
						$Question3 = $row["Question3"];
						$Question4 = $row["Question4"];
						$Question5 = $row["Question5"];
						$Quiz_score = $row["Quiz_score"];
						$Quiz_Results = $row["Quiz_Results"];

						$initial = strtoupper(substr($FullName, 0, 1)); // Get the first letter of the name and convert to uppercase
				


						echo "
										<div class='dashboard-block'>
											<div class='row'>
													<div class='col-lg-4'>
														<div class='min-100-relative'>
															<div class='initials-blcok'>
																	<div class='initials'>$initial  </div>
															</div>
															<div class='name-block'>
																<h3 class='switch-red'>$FullName</h3>
																<p class='user-email'>$VisitorEmail</p>
																<p class='user-email'>user id: $user_id</p>
															</div>
														</div>
													</div>

													<div class='col-lg-1'>
														<div class='min-100-relative'>
															<div class='video-block'>
																<p class='user-email'>$video_Id</p>
															</div>
														</div>
													</div>

													<div class='col-lg-3'>
															<div class='min-100-relative'>
																<div class='video-block'>
																	<p class='user-email'>$businessUnit</p>
																</div>
															</div>
													</div>


												<div class='col-lg-3'>
													<div class='min-100-relative'>
															<div class='video-block'>
																<p class='user-email'>$Host</p>
														</div>
													</div>
												</div>
												
										

												
											</div>
										</div>
										";
					}
				} else {
					echo "0 results";
				}
				?>
				<style>
					.dashboard-headline {
						font-size: 26px;
					}

					.video-block {
						height: 100%;
						padding: 19px 0px;
						padding-top: 52px;

					}

					.name-block {
						position: relative;
						padding-left: 70px;
					}

					.name-block h3 {
						font-size: 22px;
						margin-bottom: 5px;
					}

					.user-email {
						font-size: 14px;
						color: #666;
						margin-bottom: 0px;
						font-style: italic;
					}

					.dashboard-block {
						border-bottom: 1px solid #ccc;
						padding: 10px 0;
					}

					.min-100-relative {
						position: relative;
						min-height: 100%;
					}

					.initials-blcok {
						position: absolute;
						top: 33%;
						left: 23px;
						transform: translate(-50%, -50%);
					}

					.initials {
						display: inline-block;
						width: 50px;
						height: 50px;
						border-radius: 50%;
						background-color: #eee;
						color: #DD052B;
						border: 1px solid #DD052B;
						text-align: center;
						line-height: 50px;
						font-size: 20px;
					}
				</style>


			</div>
		</div>
	</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>