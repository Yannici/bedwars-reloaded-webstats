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
 * BedwarsRouter.class.php
 */

/**
 * A webstats dependency which handles the routing of the webstats (order, search ...)
 * 
 * @author Yannici
 *
 */
class BedwarsRouter extends BedwarsDependency
{
	
	/**
	 * The webstats instance
	 * 
	 * @var BedwarsWebstats
	 */
	private $webstats = null;
	
	/**
	 * Initializes a new instance of the BedwarsRouter-class
	 */
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
	 * Requires (includes) and displays the template
	 * 
	 * @param string $template The template which should be displayed
	 * @param BedwarsWebstats The webstats the template will get as information
	 * 
	 * @return void
	 */
	public function display($template, $webstats = null)
	{
		$this->webstats = $webstats;
		$filename = 'template/' . $template . '.php';
		
		if(!file_exists($filename)) {
			trigger_error('Couldn\'t find template file \'' . $template . '\'!', E_USER_WARNING);
			return;
		}
		
		require($filename);
	}
	
	/**
	 * Returns the webstats path
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->webstats->getPath();
	}
	
	/**
	 * Checks if the table is filtered or ordered
	 * 
	 * @return boolean
	 */
	public function isFiltered()
	{
		return (isset($_GET['bw-search']) || isset($_GET['bw-order']) || isset($_GET['bw-direction']) || isset($_GET['bw-page']));
	}
	
}

?>