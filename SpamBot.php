<?php

	if ($_POST) {
		$error = "";

		$emailArray = explode(",", $_POST["addressesBody"]);

		if (!$_POST["email"]) {
			$error.= "<li>A sender email address is required.</li>";
		}
		if (!$_POST["addressesBody"]) {
			$error.= "<li>At least one recipient email address is required.</li>";
		}
		if (!$_POST["subject"]) {
			$error.= "<li>A subject is required.</li>";
		}
		if (!$_POST["messageBody"]) {
			$error.= "<li>A message is required.</li>";
		}
		if ($_POST["email"] && (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false)) {
			$error.= "<li>The sender address is invalid.</li>";
		}

		// validate list of email addresses
		foreach ($emailArray as $value) {
			if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
				$error.= "<li>Some recipient email addresses are invalid. Remember to not include spaces in your list.</li>";
				break;
			}
		}

		if ($error != "") {
			$error = "<div class='alert alert-danger' role='alert'><p><strong>There were error(s) in your form:</strong></p>"."<ul>".$error."</ul>"."</div>";
		} else {

			foreach ($emailArray as $value) {
				$emailTo = $value;
				$subject = $_POST["subject"];
				$content = $_POST["messageBody"];
				$headers = "From: ".$_POST["email"];

				if (!mail($emailTo, $subject, $content, $headers)) {
					$error = "<div class='alert alert-danger' role='alert'><p><strong>Your message to ".$value." and all following addresses could not be sent. Please try again.</strong></p></div>";
					break;
				}
			}
			$successMessage = "<div class='alert alert-success' role='alert'><p><strong>The messages were successfully sent!</strong></p></div>";
		}
	}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>PHP SpamBot</title>
		<style>
			h1 {
				margin-bottom: 20px;
			}
			#form-container {
				margin: 20px;
			}
			h1 img {
				height: 80px;
				margin-left: 20px;
			}
		</style>
  </head>
  <body>

		<div id="form-container" class="container">
    	<h1>Spam Bot<img src="spambot-image.jpg"></h1>

			<div id="errorDiv"><? echo $error.$successMessage; ?></div>
			
			<form method="post">
				<div class="form-group">
					<label for="email">Sender email address</label>
					<input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com">
				</div>
				<div class="form-group">
					<label for="addressesBody">Enter comma-separated recipient email list below with no spaces</label>
					<textarea class="form-control" name="addressesBody" id="addressesInput" rows="3" placeholder="name1@example.com,janedoe@hotmail.com,edward.snowden@nsa.gov"></textarea>
				</div>
				<div class="form-group">
					<label for="subject">Subject</label>
					<input type="text" name="subject" class="form-control" id="subjectInput" placeholder="Re: Your new certified pre-owned Nissan Versa!">
				</div>
				<div class="form-group">
					<label for="messageBody">Email message body:</label>
					<textarea class="form-control" name="messageBody" id="bodyInput" rows="5"></textarea>
				</div>
				<button id="submitButton" type="submit" class="btn btn-primary">Submit</button>
			</form>

		</div>

		<!-- Bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  
		<!-- my script -->
		<script>

			// front-end validation 
			$("form").submit(function(e) {
				// e.preventDefault();

				let errorString = "";

				if ($("#emailInput").val() == "") {
					errorString += "<li>The sender email field is blank.</li>"; 
				}

				if ($("#addressesInput").val() == "") {
					errorString += "<li>The recipients email field is blank.</li>"; 
				}

				if ($("#subjectInput").val() == "") {
					errorString += "<li>The subject field is blank.</li>"; 
				}

				if ($("#bodyInput").val() == "") {
					errorString += "<li>The body field is blank.</li>"; 
				}

				if (errorString != "") {
					// there were errors
					$("#errorDiv").html("<div class='alert alert-danger' role='alert'><p><strong>There were error(s) in your form:</strong></p>"+"<ul>"+errorString+"</ul>"+"</div>");
					return false;
				} else {
					// there were no errors
					// $("form").unbind("submit").submit();
					return true;
				}

			})

		</script>

	</body>
</html>
