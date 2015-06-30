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

class BedwarsPlayerStats extends BedwarsDatabaseEntry
{
	
	public static $COLUMNS = array(
			'name',
			'uuid',
			'kills',
			'deaths',
			'kd',
			'games',
			'wins',
			'loses',
			'destroyedBeds',
			'score'
	);
	
	public function __construct($entry = array())
	{
		parent::__construct($entry);
	}
	
	/**
	 * @see BedwarsDatabaseEntry::getColumns()
	 */
	public function getColumns()
	{
		return self::$COLUMNS;
	}
	
	/**
	 * Sets default of every field of the entry
	 */
	public function setDefault()
	{
		$this->setValue('kills', 0);
		$this->setValue('deaths', 0);
		$this->setValue('wins', 0);
		$this->setValue('loses', 0);
		$this->setValue('destroyedBeds', 0);
		$this->setValue('score', 0);
		$this->setValue('games', 0);
		$this->setValue('kd', 0);
	}
	
	/**
	 * Validates the entry and returns if the entry is ready for saving
	 * 
	 * @return boolean
	 */
	public function validateEntry()
	{
		foreach(self::$COLUMNS as $column) {
			if(!isset($this->entry[$column])) {
				return false;
			}
		}
		
		return true;
	}
	
}