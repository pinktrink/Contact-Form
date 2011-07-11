<?php
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
?>