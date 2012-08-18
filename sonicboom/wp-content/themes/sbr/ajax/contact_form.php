<?php
include('ajaxConfig.php');
include($ajaxConfig['path'] . 'wp-load.php');
$emailHelper = new emailHelper(105);
$emailData = $emailHelper->emailData($_POST);
$headers = 'From: '.$emailData->name.' <'.$emailData->emailFrom.'>' . "\r\n";

wp_mail('gray8110@gmail.com',$emailData->subject,$emailData->message,$headers);

markupHelper::emailConfirmation();