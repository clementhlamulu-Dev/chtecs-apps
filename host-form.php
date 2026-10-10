<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . "/includes/db-connect.php";
$pageTitle = "Invite a Visitor";
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo $pageTitle ?> | CH Tecs Visitor Induction</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>
</head>


<body id="host-form">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<?php include('includes/report-tools.php'); ?>

	<div id="selectable-content">
		<div class="container">


			<?php




			$nameErr = $emailErr = $BusinessErr = $HostErr = "";
			$FullName = $VisitorEmail = $businessUnit = $Uploadmeassege = $UploadmeassegeError = $linkUserTest = $linkUserLive = "";
			$upload_ready = 1;
			$emailWarning = $inviteLink = "";

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

								error_log("Invitation email to $VisitorEmail failed: {$mail->ErrorInfo}");
								$Uploadmeassege = "";
								$emailWarning = "The invitation was saved, but the email could not be sent. Please share this link with the visitor:";
								$inviteLink = "https://dev-apps.chtecs.co.za/visitor-page.php?id=$last_id";

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

			<?php
			$departments = [
				"Human Resources" => "Human Resources",
				"Finance" => "Finance",
				"Information Technology" => "Information Technology",
				"Marketing" => "Marketing",
			];
			$hosts = ["Humphrey Maluleke", "Regional Chauke", "Vellie Mbiza", "Moses Mabunda"];
			$selectedUnit = $_POST['businessUnit'] ?? '';
			$selectedHost = $_POST['Host'] ?? '';
			?>

			<div class="invit-form">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

					<div class="main-page-heading">
						<span class="eyebrow">Host tools</span>
						<h1 class="switch-dblue">Invite a Visitor</h1>
						<p>Enter the visitor's details below to send them an induction invitation.</p>
					</div>

					<?php if (!empty($Uploadmeassege)): ?>
						<div class="app-alert app-alert-success" role="status">
							<span aria-hidden="true">&#10003;</span>
							<span><?php echo $Uploadmeassege ?></span>
						</div>
					<?php endif; ?>
					<?php if (!empty($emailWarning)): ?>
						<div class="app-alert app-alert-warning" role="alert">
							<span aria-hidden="true">!</span>
							<span><?php echo $emailWarning ?><br>
								<a href="<?php echo $inviteLink ?>" class="invite-link"><?php echo $inviteLink ?></a></span>
						</div>
					<?php endif; ?>
					<?php if (!empty($UploadmeassegeError)): ?>
						<div class="app-alert app-alert-error" role="alert">
							<span aria-hidden="true">!</span>
							<span><?php echo $UploadmeassegeError ?></span>
						</div>
					<?php endif; ?>

					<div class="form-field<?php echo $nameErr ? ' has-error' : ''; ?>">
						<label for="FullNAme">Visitor name<span class="required-field">&nbsp;*</span></label>
						<input type="text" id="FullNAme" placeholder="e.g. Clement Maluleke" name="FullNAme"
							autocomplete="name" value="<?php echo $FullName ?>" required>
						<span class="error-message"><?php echo $nameErr ?></span>
					</div>

					<div class="form-field<?php echo $emailErr ? ' has-error' : ''; ?>">
						<label for="Email">Visitor email<span class="required-field">&nbsp;*</span></label>
						<p class="warning-text" id="email-help">An induction invitation will be sent to this email address.</p>
						<input type="email" id="Email" placeholder="example@gmail.com" name="Email" autocomplete="email"
							aria-describedby="email-help" value="<?php echo $VisitorEmail ?>" required>
						<span class="error-message"><?php echo $emailErr ?></span>
					</div>

					<div class="form-field<?php echo $BusinessErr ? ' has-error' : ''; ?>">
						<label for="Select-business" id="business-ulabel">Business department<span
								class="required-field">&nbsp;*</span></label>
						<select name="businessUnit" id="Select-business" required>
							<option value="">Select a department</option>
							<?php foreach ($departments as $value => $label): ?>
								<option value="<?= $value ?>" <?= $selectedUnit === $value ? 'selected' : '' ?>><?= $label ?></option>
							<?php endforeach; ?>
						</select>
						<span class="error-message"><?php echo $BusinessErr ?></span>
					</div>

					<div class="form-field<?php echo $HostErr ? ' has-error' : ''; ?>">
						<label for="Select-host" id="host-label">Host<span class="required-field">&nbsp;*</span></label>
						<select name="Host" id="Select-host" required>
							<option value="">Select a host</option>
							<?php foreach ($hosts as $hostName): ?>
								<option value="<?= $hostName ?>" <?= $selectedHost === $hostName ? 'selected' : '' ?>><?= $hostName ?></option>
							<?php endforeach; ?>
						</select>
						<span class="error-message"><?php echo $HostErr ?></span>
					</div>

					<div class="form-actions">
						<button class="submit-btn" type="submit" id="send-invite">Send Invitation</button>
						<a href="admin-dashboard.php" class="submit-btn-dashbord">View Dashboard</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>

	<script>
		// Prevent double submissions while the invitation email is being sent.
		document.querySelector('.invit-form form').addEventListener('submit', function () {
			var btn = document.getElementById('send-invite');
			btn.classList.add('disabled');
			btn.textContent = 'Sending…';
		});
	</script>
</body>

</html>
