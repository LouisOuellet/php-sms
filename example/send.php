#!/usr/bin/env php
<?php

// These must be at the top of your script, not inside a function
use LaswitchTech\phpSMS\phpSMS;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Initiate phpSMS
$phpSMS = new phpSMS();

// Configure phpSMS
$phpSMS->config('provider','twilio')
       ->config('sid', 'your_account_sid')
       ->config('token', 'your_auth_token')
       ->config('phone', 'your_twilio_phone_number');

// Send SMS
$Response = $phpSMS->send('+1234567890','Hello from Twilio using phpSMTP!');

// Dump Result
var_dump($Response);
