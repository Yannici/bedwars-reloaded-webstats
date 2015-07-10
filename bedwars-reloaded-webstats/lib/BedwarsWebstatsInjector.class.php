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
 * BedwarsWebstatsInjector.class.php
 */

/**
 * This is the dependency injector of the webstats(-api)
 * It is the core of the webstats and collects all dependencies
 * 
 * @author Yannici
 *
 */
class BedwarsWebstatsInjector
{
	
	/**
	 * The prefix for every database table related to bedwars-reloaded
	 * 
	 * @var string
	 */
	const DB_PREFIX = 'bw_';
	
	/**
	 * The database connection
	 * 
	 * @var BedwarsDatabase
	 */
	private $db = null;
	
	/**
	 * The webstats configuration
	 * 
	 * @var array
	 */
	private $config = null;
	
	/**
	 * The router of the webstats
	 * 
	 * @var BedwarsRouter
	 */
	private $router = null;
	
	/**
	 * The texts which are used by the webstats template
	 * 
	 * @var array
	 */
	private $texts = null;
	
	/**
	 * The current instance of the injector
	 * 
	 * @var BedwarsWebstatsInjector
	 */
	private static $instance = null;
	
	/**
	 * Initialize a new instance of BedwarsWebstatsInjector class
	 * 
	 * @param array $config The configuration for the webstats
	 * @param array $texts The texts used in the webstats (locale)
	 */
	public function __construct($config, $texts)
	{
		self::$instance = $this;
		
		$this->config = $config;
		$this->texts = $texts;
		
		$this->router = new BedwarsRouter();
		
		if(!isset($config['database'])) {
			$this->db = new BedwarsDatabase(array('database' => array()));
			return;
		}
		
		$this->db = new BedwarsDatabase($config['database']);
	}
	
	/**
	 * Get language text by key
	 * 
	 * @param string $key
	 * 
	 * @return string Text
	 */
	public function _($key)
	{
		return $this->texts[$key];
	}
	
	/**
	 * Initialize the dependencies
	 * 
	 * @return void
	 */
	public function initialize()
	{
		try {
			$this->db->open();
		} catch(Exception $ex) {
			trigger_error('Error while initialize bedwars webstats: ' . $ex->getMessage(), E_USER_WARNING);
		}
	}
	
	/**
	 * On destruct the dependencies should close
	 * 
	 * @return void
	 */
	public function __destruct()
	{
		$this->db->close();
	}
	
	/**
	 * Returns the current BedwarsWebstatsInjector instance
	 * 
	 * @return BedwarsWebstatsInjector
	 */
	public static function getInstance()
	{
		return self::$instance;
	}
	
	/**
	 * Returns the current config
	 * 
	 * @return array Configuration for webstats
	 */
	public function getConfig()
	{
		return $this->config;
	}
	
	/**
	 * Sets the current config
	 * 
	 * @param array $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}
	
	/**
	 * Returns the database instance
	 *
	 * @return BedwarsDatabase
	 */
	public function getDB()
	{
		return $this->db;
	}
	
	/**
	 * Returns the pdo database connection
	 * 
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->db->getConnection();
	}
	
	/**
	 * Returns the configured path to the webstats-folder
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->config['path'];
	}
	
	/**
	 * Returns the router of this application
	 * 
	 * @return BedwarsRouter
	 */
	public function getRouter()
	{
		return $this->router;
	}
	
}

?>