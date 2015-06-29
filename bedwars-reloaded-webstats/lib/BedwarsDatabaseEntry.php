<?php
/* 
  ____           _                          
 |  _ \         | |                         
 | |_) | ___  __| __      ____ _ _ __ ___   
 |  _ < / _ \/ _` \ \ /\ / / _` | '__/ __|  
 | |_) |  __| (_| |\ V  V | (_| | |  \__ \  
 |____/ \___|\__,_| \_/\_/ \__,_|_|  |___/_ 
 |  __ \    | |               | |        | |
 | |__) |___| | ___   __ _  __| | ___  __| |
 |  _  // _ | |/ _ \ / _` |/ _` |/ _ \/ _` |
 | | \ |  __| | (_) | (_| | (_| |  __| (_| |
 |_|  \_\___|_|\___/ \__,_|\__,_|\___|\__,_|
                                  by Yannici
 
 */

abstract class BedwarsDatabaseEntry extends BedwarsDependency
{
	
	private $entry = null;
	
	public function __construct($entry = array())
	{
		parent::__construct();
		
		$this->entry = $entry;
	}
	
	/**
	 * Validates the entry checks if it is ready to store
	 */
	public abstract function validateEntry();
	
	/**
	 * Sets the default values for all fields
	 */
	public abstract function setDefault();

	/**
	 * Returns the full entry
	 * @return array <string>
	 */
	public function getEntry()
	{
		return $this->entry;
	}
	
	/**
	 * Checks if this is a new entry or a entry which already exists
	 * 
	 * @return boolean
	 */
	public function isNew()
	{
		return ($this->getValue('id') === null || $this->getValue('id') <= 0);
	}
	
	/**
	 * Sets a value of a specific field (key)
	 * 
	 * @param string $key Fieldname
	 * @param string $value Value
	 */
	public function setValue($key, $value)
	{
		$this->entry[$key] = $value;
	}
	
	/**
	 * Returns a value of a player stats field by key
	 *
	 * @param string $key
	 */
	public function getValue($key)
	{
		if(!isset($this->entry[$key])) {
			return null;
		}
		
		return $this->entry[$key];
	}
	
}