<?php
	
	// Configuration Settings
	$mail_to = "youremail@domain.com";
	$subject = "Contact Form Submission";
	$from = "contact@sitename.com";
	
	// Basic header information
	$header = "From: <$from>\n";
	$header .= "Return-path: <$from>\n";
	$header .= "X-Sender-IP: " .$_SERVER['REMOTE_ADDR']."\n";
	$header .= "Content-Type: text/html; \n charset=iso-8859-1 \n";
	
	// Construct the basic HTML for the message
	$head = "<html><body>";
	$table_start = "<table border='1' width='100%'><td align='center'><strong>Field</strong></td><td align='center'><strong>Value</strong></td>";
	
	// Fetch all the form fields and their values
	$text = "";
	foreach($_POST as $name => $value) {
		$text .= "<tr><td>$name</td><td>$value</td></tr>";
	}

	// End the table and add extra footer information
	$table_end = "</table>";
	$info = "<br />Message sent from: ".$_SERVER['SERVER_NAME'];
	$footer = "</body></html>";

	// Combine all the information
	$body = "$head $table_start $text $table_end $info $foot";

	// If everything is filled out correctly, send the e-mail
	if ($body != "") {
		echo 'Your message sent successfully. Thank you for contacting us.';
		mail($mail_to, $subject, $body, $header);
	}

?>