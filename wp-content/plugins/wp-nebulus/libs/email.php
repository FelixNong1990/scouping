<?php
    $to = 'mail@sonnyt.com';
    $subject = $_POST['name'].' Subscribed';
    $from = $_POST['email'];
    $message = 'Name: '.$_POST['name'].' <br /> Email: '.$_POST['email'];

    $headers .= "Reply-To: ". $to ."\r\n";
    $headers .= "Return-Path: ". $to ."\r\n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
    
    // Additional headers
    $headers .= 'From: MOONlander <". $to .">' . "\r\n";

    mail($to, $subject, $message, $headers);

    echo "Got it, you've been added to our email list.";