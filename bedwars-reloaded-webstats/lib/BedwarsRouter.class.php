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

class BedwarsRouter extends BedwarsDependency
{
	
	private $webstats = null;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Returns the webstats instance
	 * 
	 * @return BedwarsWebstats
	 */
	public function getWebstats()
	{
		return $this->webstats;
	}
	
	/**
	 * Requires and displays the template
	 * 
	 * @param string $template
	 */
	public function display($template, $webstats = null)
	{
		$this->webstats = $webstats;
		$filename = 'template/' . $template . '.php';
		
		if(!file_exists($filename)) {
			trigger_error('Couldn\'t find template file \'' . $template . '\'!', E_WARNING);
			return;
		}
		
		require($filename);
	}
	
}

?>