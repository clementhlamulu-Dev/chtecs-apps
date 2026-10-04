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

				<div class="col-lg-12 mt-5 mb-5">


					<table width="100%" border="0" cellspacing="0" cellpadding="3" class="fin-tbl">
						<tr>
							<td width="50" class="finthick-red finright"><strong>User ID </strong></td>
							<td width="80" class="finthick-red finright"><strong>Video ID </strong></td>
							<td class="finthick-red finright"><strong>Full name </strong></td>
							<td class="finthick-red finright">admin-dashboard</td>
							<td  class="finthick-red finright"><strong>Business Department </strong></td>
							<td  class="finthick-red finright"><strong>Host</strong></td>
						</tr>

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
								$Question1 = $row[" Question1"];
								$Question2 = $row["Question2"];
								$Question3 = $row["Question3"];
								$Question4 = $row["Question4"];
								$Question5 = $row["Question5"];
								$Quiz_score = $row["Quiz_score"];
								$Quiz_Results = $row["Quiz_Results"];

								echo "
										<tr>
											<td width='50'   class='finred finright'>$user_id</td>
											<td width='80' class='finred finright'>$video_Id</td>
											<td class='finred finright finright'>$FullName</td>
											<td class='finred finright finright'>$VisitorEmail</td>
											<td  class='finred finright'>$businessUnit</td>
											<td ' class='finred finright'>$Host</td>
										</tr>
										";
							}
						} else {
							echo "0 results";
						}


						?>



						<tr>
							<td width="50" height="10" class="finthick-red "></td>
							<td width="80" height="10"  class="finthick-red "></td>
							<td class="finthick-red " height="10" ></td>
							<td class="finthick-red " height="10" ></td>
							<td   height="10"  class="finthick-red "></td>
							<td  height="10"  class="finthick-red "></td>
						</tr>
					</table>

				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>