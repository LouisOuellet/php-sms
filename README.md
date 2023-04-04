![GitHub repo logo](/dist/img/logo.png)

# phpSMS
![License](https://img.shields.io/github/license/LouisOuellet/php-sms?style=for-the-badge)
![GitHub repo size](https://img.shields.io/github/repo-size/LouisOuellet/php-sms?style=for-the-badge&logo=github)
![GitHub top language](https://img.shields.io/github/languages/top/LouisOuellet/php-sms?style=for-the-badge)
![Version](https://img.shields.io/github/v/release/LouisOuellet/php-sms?label=Version&style=for-the-badge)

## Description
The phpSMS class is a PHP library designed to simplify the process of sending SMS messages using the Twilio API. It provides an easy-to-use interface for configuring and managing SMS-related settings, as well as handling the actual SMS sending process.

## Features
  - Supported providers: The class currently supports Twilio as the SMS provider with a predefined API URL in the Providers constant.
  - Configurable settings: The library allows for the configuration of various settings such as the SMS provider, SID, token, and phone number using the config() method.
  - SMS sending: The send() method is responsible for sending SMS messages using the configured provider. It validates the required settings (provider, SID, token, and phone number), constructs the API URL, sets up the cURL request, and handles any errors that may occur during the process.
  - Error handling and logging: The library uses a dedicated phpLogger instance to log any errors that occur during configuration or SMS sending. It also uses exceptions for error handling and reporting.
  - Configuration persistence: The config() method saves the updated configuration values to the phpConfigurator instance, ensuring that the configuration is persistent across different instances of the class.

## Why you might need it
In summary, the phpSMS class can be a valuable addition to your PHP application if you need a simple, organized, and extensible way to send SMS messages using the Twilio API. It streamlines the process of SMS sending, manages configurations, and provides error handling and logging capabilities, all within a single, easy-to-use class.

## Can I use this?
Sure!

## License
This software is distributed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.en.html) license. Please read [LICENSE](LICENSE) for information on the software availability and distribution.

## Requirements
* PHP >= 7.3.0

## Security
Please disclose any vulnerabilities found responsibly – report security issues to the maintainers privately.

## Installation
Using Composer:
```sh
composer require laswitchtech/php-sms
```

## How do I use it?
### Example
```php
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
```
