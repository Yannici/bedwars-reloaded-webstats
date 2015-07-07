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

/**
 * BedwarsDependency.class.php
 */

/**
 * Is the superclass of every dependency in the webstats
 * When this is a superclass of a class the specific class have
 * access to the current injector instance
 * 
 * @author Yannici
 *
 */
abstract class BedwarsDependency
{
	
	/**
	 * Initializes a new instance of BedwarsDependency-class
	 */
	public function __construct()
	{
		// nothing to do
	}
	
	/**
	 * Returns the current instance of the injector
	 * 
	 * @return BedwarsWebstatsInjector
	 */
	public function getInjector()
	{
		return BedwarsWebstatsInjector::getInstance();
	}
	
}

?>