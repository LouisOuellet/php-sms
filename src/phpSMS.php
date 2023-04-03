<?php

// Declaring namespace
namespace LaswitchTech\phpSMS;

// Import phpConfigurator class into the global namespace
use LaswitchTech\phpConfigurator\phpConfigurator;

// Import phpLogger class into the global namespace
use LaswitchTech\phpLogger\phpLogger;

// Importing Dependencies
use Exception;

class phpSMS {

  // Constants
  const Providers = [
    'twilio' => 'https://api.twilio.com/2010-04-01/Accounts/%SID%/Messages.json',
  ];

	// Logger
	private $Logger;
	private $Level = 1;

  // Configurator
  private $Configurator = null;

  // cURL
  private $cURL = null;
  private $URL = null;
  private $Provider = null;
  private $SID = null;
  private $Token = null;
  private $Phone = null;

  /**
   * Create a new phpSMS instance.
   *
   * @return object $this
   */
  public function __construct(){

    // Initialize Configurator
    $this->Configurator = new phpConfigurator('sms');

    // Retrieve Log Level
    $this->Level = $this->Configurator->get('logger', 'level') ?: $this->Level;

    // Initiate phpLogger
    $this->Logger = new phpLogger('sms');

    // Configure phpSMS
    $this->Provider = $this->Configurator->get('sms', 'provider') ?: $this->Provider;
    $this->SID = $this->Configurator->get('sms', 'sid') ?: $this->SID;
    $this->Token = $this->Configurator->get('sms', 'token') ?: $this->Token;
    $this->Phone = $this->Configurator->get('sms', 'phone') ?: $this->Phone;
  }

  /**
   * Configure Library.
   *
   * @param  string  $option
   * @param  bool|int  $value
   * @return void
   * @throws Exception
   */
  public function config($option, $value){
		try {
			if(is_string($option)){
	      switch($option){
	        case"provider":
	          if(is_string($value)){
              if(isset(self::Providers[$value])){

                // Set Provider
  	            $this->Provider = $value;

                // Save to Configurator
                $this->Configurator->set('sms',$option, $value);
              } else {
                throw new Exception("Service provider not supported.");
              }
	          } else{
	            throw new Exception("2nd argument must be a string.");
	          }
	          break;
	        case"token":
	          if(is_string($value)){

              // Set Token
	            $this->Token = $value;

              // Save to Configurator
              $this->Configurator->set('sms',$option, $value);
	          } else{
	            throw new Exception("2nd argument must be a string.");
	          }
	          break;
	        case"phone":
	          if(is_string($value)){

              // Set Phone
	            $this->Phone = $value;

              // Save to Configurator
              $this->Configurator->set('sms',$option, $value);
	          } else{
	            throw new Exception("2nd argument must be a string.");
	          }
	          break;
	        case"sid":
	          if(is_string($value)){

              // Set SID
	            $this->SID = $value;

              // Save to Configurator
              $this->Configurator->set('sms',$option, $value);
	          } else{
	            throw new Exception("2nd argument must be a string.");
	          }
	          break;
	        default:
	          throw new Exception("unable to configure $option.");
	          break;
	      }
	    } else{
	      throw new Exception("1st argument must be as string.");
	    }
		} catch (Exception $e) {

      // Log Exception
			$this->Logger->error('Error: '.$e->getMessage());
		}

    return $this;
  }

  public function send($Number, $Body){
    try{

      // Check if Provider was configured
      if(!$this->Provider){

        // Throw Exception
        throw new Exception("Provider not configured.");
      }

      // Check if Phone was configured
      if(!$this->Phone){

        // Throw Exception
        throw new Exception("Phone number not configured.");
      }

      // Check if SID was configured
      if(!$this->SID){

        // Throw Exception
        throw new Exception("SID not configured.");
      }

      // Check if Token was configured
      if(!$this->Token){

        // Throw Exception
        throw new Exception("Token not configured.");
      }

      // If URL was generated, generate it
      if(!$this->URL){
        $URL = self::Providers[$this->Provider];
        $URL = str_replace('%SID%',$this->SID,$URL);
        $URL = str_replace('%Token%',$this->Token,$URL);
        $URL = str_replace('%Phone%',$this->Phone,$URL);
        $this->URL = $URL;
      }

      // Construct Data Array
      $Data = [
        'From' => $this->Phone,
        'To' => $Number,
        'Body' => $Body,
      ];

      // Initiate cURL
      $this->cURL = curl_init();

      // Configure cURL
      curl_setopt($this->cURL, CURLOPT_URL, $this->URL);
      curl_setopt($this->cURL, CURLOPT_POST, 1);
      curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $Data);
      curl_setopt($this->cURL, CURLOPT_USERPWD, "{$this->SID}:{$this->Token}");
      curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

      // Execute cURL
      $Response = curl_exec($this->cURL);

      // Check for cURL errors
      if (curl_errno($this->cURL)) {

        // Throw Exception
        throw new Exception('Error: ' . curl_error($this->cURL));
      } else {

        // Decode the JSON response
        $Response = json_decode($Response, true);
      }

      // Close the cURL session
      curl_close($this->cURL);

      // Return
      return $Response;
    } catch (Exception $e) {

      // Log Exception
			$this->Logger->error('Error: '.$e->getMessage());
		}
  }
}
