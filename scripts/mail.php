<?php
	include 'validation.php';
	
	// Configuration Settings
	$config['to'][] = 'youremail@example.com';
	$config['to'][] = 'otheremail@example.com';
	$config['cc'][] = 'cc1@example.com';
	$config['cc'][] = 'cc2@example.com';
	$config['bcc'][] = 'bcc1@example.com';
	$config['bcc'][] = 'bcc2@example.com';
	
	define('EMAIL_SUBJECT', 'Contact Form Submission');
	define('EMAIL_FROM', 'no-reply@example.com');
	define('OBTAIN_INFORMATION', 'none');  //'all' or 'none'
	//My suggestion would be to leave OBTAIN_INFORMATION as 'none' and add to the $except array as necessary
	//This will ensure that exterraneous post values don't get sent in the email (junkdata attacks could succeed otherwise)
	
	$except = array(
		'fname',
		'lname',
		'email',
		'address',
		'city',
		'state',
		'zip',
		'comments'
	);
	
	$validate = array(
		'keepempty' => 'blank',
		'name' => 'filled|name|max:64',
		'lname' => 'filled|name|max:64',
		'email' => 'regex:email',
		'address' => 'optional|max:128',
		'city' => 'optional|max:64',
		'state' => 'optional|size:2|letters',
		'zip' => 'optional|size:5|numeric|>:5',
		'comments' => 'optional|max:256'
	);
	
	// Basic header information
	$headers = array(
		'From' =>  "<$from>",
		'Return-path' => "<$from>",
		'X-Sender-IP' => $_SERVER['REMOTE_ADDR'],
		'Content-Type' => 'text/html; \n charset=iso-8859-1 '
	);
	
	$errors = array();
	
	// Construct the basic HTML for the message
	$body = '<html><body><table border=\'1\' width=\'100%\'><td align=\'center\'><strong>Field</strong></td><td align=\'center\'><strong>Value</strong></td>';
	
	// Fetch all the form fields and their values
	switch(OBTAIN_INFORMATION){  //The following code could be minimized, but that would introduce redundancy. In this case it's either optimized or minimized.
		case 'none':
			foreach($_POST as $key => &$value){
				if(isset($validate[$key])){
					foreach(preg_split('/(?<!\\\\)\\|/', $validate[$key]) as $validation){
						$validation = str_replace('\\|', '|', $validation);
						$extra = NULL;
						$args = explode(':', $validation, 2);
						$method = array_shift($args);
						if(count($args)) $extra = array_shift($args);
						$extraarg = $extra;
						if($extra[0] === '['){
							$extraarg = array();
							foreach(preg_split('/(?<!\\\\),/', substr($extra, 1, -1)) as $entity)
								$extraarg[] = trim(str_replace('\\,', ',', $entity));
						}
						if(isset(Validation::$maps[$method]))
							$method = Validation::$maps[$method];
						elseif(isset(Validation::$regex[$method])){
							$extraarg = $method;
							$method = 'regex';
						}elseif($method[0] === '!'){
							$extraarg = substr($method, 1);
							$method = 'regex';
						}
						if(!call_user_func(array('Validation', $method), $value, $extraarg)){
							$errors[$key] = Validation::$lasterror;
							break;
						}
					}
				}
				if(in_array($key, $except))
					$body .= "<tr><td>$name</td><td>$value</td></tr>";
			}
			break;
		case 'all':
			foreach($_POST as $key => $value){
				if(isset($validate[$key])){
					foreach(preg_split('/(?<!\\\\)\\|/', $validate[$key]) as $validation){
						$validation = str_replace('\\|', '|', $validation);
						$extra = NULL;
						$args = explode(':', $validation, 2);
						$method = array_shift($args);
						if(count($args)) $extra = array_shift($args);
						$extraarg = $extra;
						if($extra[0] === '['){
							$extraarg = array();
							foreach(preg_split('/(?<!\\\\),/', substr($extra, 1, -1)) as $entity)
								$extraarg[] = trim(str_replace('\\,', ',', $entity));
						}
						if(isset(Validation::$maps[$method]))
							$method = Validation::$maps[$method];
						elseif(isset(Validation::$regex[$method])){
							$extraarg = $method;
							$method = 'regex';
						}elseif($method[0] === '!'){
							$extraarg = substr($method, 1);
							$method = 'regex';
						}
						if(!call_user_func(array('Validation', $method), $value, $extraarg)){
							$errors[$key] = Validation::$lasterror;
							break;
						}
					}
				}
				if(!in_array($key, $except))
					$body .= "<tr><td>$name</td><td>$value</td></tr>";
			}
	}

	// End the table and add extra footer information
	$body .= "</table><br />Message sent from: {$_SERVER['SERVER_NAME']}</body></html>";
	
	if(isset($config['cc']) && count($config['cc'])) $headers['Cc'] = implode(',', $config['cc']);
	if(isset($config['bcc']) && count($config['bcc'])) $headers['Bcc'] = implode(',', $config['bcc']);

	// If everything is filled out correctly, send the e-mail
	if(!count($errors)){
		$hstr = '';
		foreach($headers as $key => $val) $hstr .= "$key: $val\n";
		echo 'Your message sent successfully. Thank you for contacting us.';
		mail(implode(',', $config['to']), $subject, $body, $hstr);
	}else{
		//Oh no! Errors!
	}

?>