<?php

//Declaring namespace
namespace LaswitchTech\phpConfigurator;

// Importing Dependencies
use Exception;

class phpConfigurator {

  const Extension = '.cfg';
  const ConfigDir = '/config';

  private $Files = [];
  private $Configurations = [];
  private $RootPath = null;

  /**
   * Create a new phpConfigurator instance.
   *
   * @return object $this
   */
  public function __construct($File = null){

    // Set RootPath according to this file
    $this->RootPath = realpath(getcwd());

    // If server document_root is available, use it instead
    if(isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT'])){
      $this->RootPath = dirname($_SERVER['DOCUMENT_ROOT']);
    }

    // If constant ROOT_PATH has been set
    if(defined("ROOT_PATH")){
      $this->RootPath = ROOT_PATH;
    }

    // Check if configuration files were provided
    if($File){
      if(is_string($File)){
        $this->add($File);
      } else {
        if(is_array($File)){
          if($this->isAssociative($File)){
            foreach($File as $Name => $Path){
              $this->add($Name, $Path);
            }
          } else {
            foreach($File as $Name){
              $this->add($Name);
            }
          }
        }
      }
    }
  }

  /**
   * Check if an array is associative.
   *
   * @param  array  $array
   * @return boolean
   */
  private function isAssociative($array) {
    $keys = array_keys($array);
    return array_keys($keys) !== $keys;
  }

  /**
   * Add a configuration file.
   *
   * @param  string  $File
   * @param  string  $Path
   * @return object $this
   */
  public function add($File, $Path = null){

    // If not already saved, add File in the list
    if(!isset($this->Files[$File])){

      // Set Path
      if(!is_string($Path)){
        $Path = $this->RootPath . self::ConfigDir . '/' . $File . self::Extension;
      }

      // Check if it doesn't exist
      if(!is_file($Path)){

        // Create the directory recursively
        if(!is_dir(dirname($Path))){
          mkdir(dirname($Path), 0777, true);
        }

        // Create File
        file_put_contents($Path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      }

      // Retrieve File and Load it
      $this->Configurations[$File] = json_decode(file_get_contents($Path),true);

      // Save File
      $this->Files[$File] = $Path;
    }

    // Return
    return $this;
  }

  /**
   * Delete a or all configuration file(s).
   *
   * @param  string|null  $File
   * @return object $this
   */
  public function delete($File = null){

    // If not already saved, add File in the list
    if(isset($this->Files[$File])){

      // Delete Configuration File
      unlink($this->Files[$File]);

      // Unset File
      unset($this->Files[$File]);
    }

    // Return
    return $this;
  }

  /**
   * Get a Setting.
   *
   * @param  string  $File
   * @param  string  $Setting
   * @return object $this
   */
  public function get($File, $Setting){

    // Check if file and setting exist and return it.
    if(isset($this->Configurations[$File],$this->Configurations[$File][$Setting])){

      // Return
      return $this->Configurations[$File][$Setting];
    }
  }

  /**
   * Set a Setting.
   *
   * @param  string  $File
   * @param  string  $Setting
   * @param  string|array|int|boolean  $Value
   * @return object $this
   */
  public function set($File, $Setting, $Value){

    // Check if file exist and return it.
    if(isset($this->Configurations[$File])){

      // Set Value
      $this->Configurations[$File][$Setting] = $Value;

      // Save File
      file_put_contents($this->Files[$File], json_encode($this->Configurations[$File], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    // Return
    return $this;
  }
}
