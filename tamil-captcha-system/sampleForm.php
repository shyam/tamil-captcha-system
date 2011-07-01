<?php 
header('Content-Type: text/html; charset=UTF-8'); 
session_start();

if( isset($_POST['submit'])) {
   if( $_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code'] ) ) {
		// Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 
		echo 'Thank you. Your message said "'.$_POST['message'].'"';
		echo "<br><br>";
		echo $_SESSION['security_code'];
		echo "<br><br>";
		echo $_POST['security_code'];
		echo "<br><br>unsetting the session!";
		unset($_SESSION['security_code']);
   } else {
		// Insert your code for showing an error message here
		echo 'Sorry, you have provided an invalid security code';
		echo "<br><br>";
		echo $_SESSION['security_code'];
		echo "<br><br>";
		echo $_POST['security_code'];
   }
} else {
?>
	<head>
		<meta http-equiv="Content-type" value="text/html; charset=UTF-8" />
	</head>
	<form action="" method="post" accept-charset="utf-8">
		<label for="name">Name: </label><input type="text" name="name" id="name" /><br />
		<label for="email">Email: </label><input type="text" name="email" id="email" /><br />
		<label for="message">Message: </label><textarea rows="5" cols="30" name="message" id="message"></textarea><br />
		<img src="tamil-captcha-system/?characters=6" /><br />
		<label for="security_code">Security Code: </label><input id="security_code" name="security_code" type="text" /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>

<?php
	}
?>