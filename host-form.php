<?php
require_once __DIR__ . "/includes/db-connect.php";

?>


<!DOCTYPE html>
<html lang="eng">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
			<div class="row">
				<div class="col-lg-12">
					<div class="main-page-heading">
						<h1 class="switch-red text-center">
							Invite a Visitor
						</h1>
					</div>

				</div>
			</div>

			<?php




			$nameErr = $emailErr = $BusinessErr = $HostErr = "";
			$FullName = $VisitorEmail = $businessUnit = $Uploadmeassege = $UploadmeassegeError = $linkUserTest = $linkUserLive = "";
			$upload_ready = 1;
			if ($_SERVER["REQUEST_METHOD"] == "POST") {



				//validate name
				if (empty(test_input($_POST["FullNAme"]))) {

					$nameErr = "please enter Vistor's full name";
					$upload_ready = 0;
				} else {

					$FullName = test_input($_POST["FullNAme"]);
					if (!preg_match("/^[a-zA-Z- ' ]*$/", $FullName)) {
						$nameErr = " Only letters and white space allowed";
						$upload_ready = 0;
					}

				}


				//validate email.
				if (empty(test_input($_POST["Email"]))) {

					$emailErr = "please enter visitor's Email";
					$upload_ready = 0;
				} else {

					$VisitorEmail = test_input($_POST["Email"]);
					if (!filter_var($VisitorEmail, FILTER_VALIDATE_EMAIL)) {
						$emailErr = "Enter a valid email";
						$upload_ready = 0;
					}

				}


				//validate Business
				$businessUnit = trim($_POST['businessUnit'] ?? '');
				$allowedUnits = ["Human Resources", "Finance", "Information Technology", "Marketing"];



				if (empty($businessUnit)) {

					$errors['businessUnit'] = "Please select a department.";
					$BusinessErr = $errors['businessUnit'];
					$upload_ready = 0;

				} elseif (!in_array($businessUnit, $allowedUnits)) {

					$errors['businessUnit'] = "Invalid department selected.";
					$BusinessErr = $errors['businessUnit'];
					$upload_ready = 0;
				}





				//validate Host
				$Host = trim($_POST['Host'] ?? '');
				$allowedHosts = ["Humphrey Maluleke", "Regional Chauke", "Vellie Mbiza", "Moses Mabunda"];



				if (empty($Host)) {

					$errors['Host'] = "Please select a Host.";
					$HostErr = $errors['Host'];
					$upload_ready = 0;
				} elseif (!in_array($Host, $allowedHosts)) {

					$errors['Host'] = "Invalid host selected.";
					$HostErr = $errors['Host'];
					$upload_ready = 0;

				}




				//this execute whenever all is well qwith the form validation
				if ($upload_ready === 1) {




					//preparation for database
					$video_Id = substr($businessUnit, 0, 2) . substr($Host, 0, 2);

					$sql = "INSERT INTO InductionTable ( firstname, email, BusinessUnit, Host)
				 		VALUES ( '$FullName', '$VisitorEmail', '$businessUnit', '$Host' )";


					try {
						if ($conn->query($sql) === TRUE) {


							$last_id = $conn->insert_id;
							$Uploadmeassege = "Invitation created. You will recieve a confirmation email.";

							require_once __DIR__ . "/includes/send-confirmation-email.php";

							//$linkUserTest = "Test Link: http://localhost/chtecs-apps/visitor-page.php?id=$last_id";
							$linkUserLive = "Live Link: http://dev-app.chtecs.co.za/visitor-page.php?id=$last_id";

						} else {
							$UploadmeassegeError = "Could not save invitation. Please try again.";
						}
					} catch (mysqli_sql_exception $e) {
						$UploadmeassegeError = "Could not save invitation. Please contact support.";
					}

				} else {
					$UploadmeassegeError = "Failed to create an invitation. Please try again";
				}
			}



			function test_input($data)
			{
				$data = trim($data);
				$data = stripcslashes($data);
				$data = htmlspecialchars($data);

				return $data;
			}
			?>

			<div class="invit-form">


				<div class="row">


					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

						<div class="row">
							<div class="col-lg-12">

								<p class="Success-message"><?php echo $Uploadmeassege ?></php>
								<p class="Success-message"> <?php echo $linkUserTest ?></php>
								<p class="Success-message"> <?php echo $linkUserLive ?></php>
								<p class="error-message"><?php echo $UploadmeassegeError ?></php>
								</p>
								<label for="">Visitor name</label> <span
									class="error-message"><?php echo $nameErr ?></span>
								<input type="text" placeholder="Clement Maluleke" name="FullNAme"
									Value="<?php echo $FullName ?>">
							</div>


							<div class="col-lg-12">

								<label for="">Visitor Email</label><span
									class="error-message"><?php echo $emailErr ?></span>
								<input type="email" placeholder="example@gmail.com" name="Email"
									Value="<?php echo $VisitorEmail ?>">
							</div>


							<div class="col-lg-12">
								<label for="businessUnit" id="business-ulabel">Business Department</label><span
									class="error-message"><?php echo $BusinessErr ?></span>
								<select name="businessUnit" id="Select-business">
									<option value="">-- Select Department --</option>
									<option value="Human Resources" <?= (($_POST['businessUnit'] ?? '') === 'Human Resources') ? 'selected' : '' ?>>Human Resource</option>
									<option value="Finance" <?= (($_POST['businessUnit'] ?? '') === 'Finance') ? 'selected' : '' ?>>Finance</option>
									<option value="Information Technology" <?= (($_POST['businessUnit'] ?? '') === 'Information Technology') ? 'selected' : '' ?>>Information Technology
									</option>
									<option value="Marketing" <?= (($_POST['businessUnit'] ?? '') === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
								</select>
							</div>


							<div class="col-lg-12">
								<label for="Host" id="host-label">Host</label><span class="error-message">
									<?php echo $HostErr ?>
								</span>
								<select name="Host" id="Select-host">
									<option value="">-- Select Host --</option>
									<option value="Humphrey Maluleke" <?= (($_POST['Host'] ?? '') === 'Humphrey Maluleke') ? 'selected' : '' ?>>Humphrey Maluleke</option>
									<option value="Regional Chauke" <?= (($_POST['Host'] ?? '') === 'Regional Chauke') ? 'selected' : '' ?>>Regional Chauke</option>
									<option value="Vellie Mbiza" <?= (($_POST['Host'] ?? '') === 'Vellie Mbiza') ? 'selected' : '' ?>>Vellie Mbiza</option>
									<option value="Moses Mabunda" <?= (($_POST['Host'] ?? '') === 'Moses Mabunda') ? 'selected' : '' ?>>Moses Mabunda</option>
								</select>

								<div class="row">
									<div class="col-lg-6">

										<div class="submit-btn-block">
											<button class="submit-btn" type="submit">Send Invitatiion</button>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="submit-btn-block">
											<a href="admin-dashboard.php" class="submit-btn-dashbord" type="submit">View
												Dashbord</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>