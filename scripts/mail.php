<?php
	
	// Configuration Settings
	$config['to'][] = 'youremail@example.com';
	$config['to'][] = 'otheremail@example.com';
	$config['cc'][] = 'cc1@example.com';
	$config['cc'][] = 'cc2@example.com';
	$config['bcc'][] = 'bcc1@example.com';
	$config['bcc'][] = 'bcc2@example.com';
	$subject = "Contact Form Submission";
	$from = "contact@sitename.com";
	
	// Basic header information
	$headers = array(
		'From' =>  "<$from>",
		'Return-path' => "<$from>",
		'X-Sender-IP' => $_SERVER['REMOTE_ADDR'],
		'Content-Type' => 'text/html; \n charset=iso-8859-1 '
	);
	
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
	
	if(isset($config['cc']) && count($config['cc'])) $headers['Cc'] = implode(',', $config['cc']);
	if(isset($config['bcc']) && count($config['bcc'])) $headers['Bcc'] = implode(',', $config['bcc']);

	// If everything is filled out correctly, send the e-mail
	$hstr = '';
	foreach($headers as $key => $val) $hstr .= "$key: $val\n";
	if ($body != "") {
		echo 'Your message sent successfully. Thank you for contacting us.';
		mail(implode(',', $config['to']), $subject, $body, $hstr);
	}

?>