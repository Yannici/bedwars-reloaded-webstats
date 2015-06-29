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

class BedwarsWebstatsInjector
{
	
	public const DB_PREFIX = 'bw_';
	
	private $db = null;
	private $config = null;
	private $router = null;
	private $texts = null;
	
	private static $instance = null;
	
	/**
	 * Initialize a new instance of BedwarsWebstatsInjector class
	 * 
	 * @param array $config
	 */
	public function __construct($config, $texts)
	{
		self::$instance = $this;
		
		$this->config = $config;
		$this->texts = $texts;
		
		if(!isset($config['database'])) {
			$this->db = new BedwarsDatabase(['database' => []]);
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
	 */
	public function initialize()
	{
		try {
			$this->db->open();
		} catch(Exception $ex) {
			trigger_error('Error while initialize bedwars webstats: ' . $ex->getMessage(), E_WARNING);
		}
	}
	
	/**
	 * On destruct the dependencies should close
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
	
}

?>