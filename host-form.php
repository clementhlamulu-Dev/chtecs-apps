<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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


			<?php




			$nameErr = $emailErr = $BusinessErr = $HostErr = "";
			$FullName = $VisitorEmail = $businessUnit = $Uploadmeassege = $UploadmeassegeError = $linkUserTest = $linkUserLive = "";
			$upload_ready = 1;

			$emailpass = "";
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

							// require_once __DIR__ . "../environment-sec/config.php";
			

							// ============================================================================================
			

							require_once __DIR__ . '/../enviroment-sec/config.php';
							require __DIR__ . '/vendor/autoload.php';

							$mail = new PHPMailer(true);


							try {

								// SMTP configuration
								$mail->isSMTP();
								$mail->Host = 'mail.chtecs.co.za';
								$mail->SMTPAuth = true;
								$mail->Username = 'no-reply@chtecs.co.za';
								$mail->Password = $emailpass;
								$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
								$mail->Port = 465;

								// Sender
								$mail->setFrom(
									'no-reply@chtecs.co.za',
									'CH Technological Solutions'
								);

								// Recipient
								$mail->addAddress($VisitorEmail, $FullName);

								// BCC
								$mail->addBCC('clementhlamulu@gmail.com');

								// Email format
								$mail->isHTML(true);

								$mail->Subject = 'Visitor Induction Required';

								$mail->Body = "
										<html>
										<body>

										<p>Hi $FullName,</p>

										<p>
											You have been invited to complete the induction
											for $businessUnit.
										</p>

										<p>
											Please click the link below to watch the induction video
											and complete the short assessment.
										</p>

										<p>
											<a href='https://dev-apps.chtecs.co.za/visitor-page.php?id=$last_id'>
												Start Visitor Induction
											</a>
										</p>

										<p>Thank you.</p>

										</body>
										</html>
									";

								$mail->AltBody = "
										Hi $FullName,

										You have been invited to complete the visitor induction
										for $businessUnit.

										Please visit the induction page to complete the video
										and assessment.
									";

								$mail->send();

								//
			
							} catch (Exception $e) {

								echo "Email could not be sent. Error: {$mail->ErrorInfo}";

							}




							// ================================================================================================
			

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

								<div class="main-page-heading">
									<h1 class="mb-3 mt-2 switch-dblue">
										Invite a Visitor
									</h1>

									<p>Enter the visitor's details below to send them an induction invitation.</p>
								</div>

								<p class="Success-message"><?php echo $Uploadmeassege ?></php>
								<p class="error-message"><?php echo $UploadmeassegeError ?></php>
								</p>
								<label for="">Visitor name<span class="required-field">&nbsp;*</span></label> <span
									class="error-message"><?php echo $nameErr ?></span>

								<input type="text" placeholder="Clement Maluleke" name="FullNAme"
									Value="<?php echo $FullName ?>">
							</div>


							<div class="col-lg-12">

								<label for="">Visitor Email<span class="required-field">&nbsp;*</span></label><span
									class="error-message"><?php echo $emailErr ?></span>
								<p class="warning-text">An induction invitation will be sent to this email address.</p>
								<input type="email" placeholder="example@gmail.com" name="Email"
									Value="<?php echo $VisitorEmail ?>">

							</div>


							<div class="col-lg-12">
								<label for="businessUnit" id="business-ulabel">Business Department<span
										class="required-field">&nbsp;*</span></label><span
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
								<label for="Host" id="host-label">Host<span
										class="required-field">&nbsp;*</span></label><span class="error-message">
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