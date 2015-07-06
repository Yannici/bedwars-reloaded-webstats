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

class BedwarsWebstats extends BedwarsDependency
{
	const DB_TABLENAME = 'stats_players';
	
	/*
	 * Parameters
	 */
	private $page = null;
	private $maxPage = null;
	private $perpage = null;
	private $order = null;
	private $orderDirection = null;
	private $search = null;
	
	private $path = null;

	/*
	 * Stats
	 */
	private $stats = array();
	
	// On error
	private $errors = array();

	public function __construct()
	{
		parent::__construct();
		
		$this->load();
	}
	
	private function loadPage()
	{
		$page = 0;
		
		if(!isset($_GET['bw-page'])) {
			$page = 1;
		} else {
			$page = intval($_GET['bw-page']);
		}
		
		if($page <= 0) {
			$page = 1;
		}
		
		return $page;
	}
	
	private function loadStats()
	{
		$table = BedwarsWebstatsInjector::DB_PREFIX . self::DB_TABLENAME;
		
		$pageStart = ($this->perpage * $this->page) - $this->perpage;
		if($pageStart <= 0) {
			$pageStart = 0;
		}
		
		$stmt = null;
		$result = [];
		$rank = '(SELECT COUNT(*)+1 FROM ' . $table . ' WHERE ' . $this->order . ' >  bw.' . $this->order . ') AS rank';
		
		if($this->search !== null) {
			$search = '%' . $this->search . '%';
			$stmt = $this->getInjector()->getDB()->prepare("SELECT *, " . $rank . " FROM " . $table . " bw WHERE `name` LIKE :name ORDER BY " . $this->order . " " . $this->orderDirection . " LIMIT " . $pageStart . ", " . $this->perpage);
			$stmt->bindParam(':name', $search, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
		} else {
			$stmt = $this->getInjector()->getDB()->query('SELECT *, ' . $rank . ' FROM ' . $table . ' bw ORDER BY ' . $this->order . ' ' . $this->orderDirection . ' LIMIT ' . $pageStart . ', ' . $this->perpage);
			$result = $stmt;
		}
		
		if(!$stmt) {
			trigger_error('Couldn\'t fetch data for webstats! Please contact the administrator!', E_USER_WARNING);
			return;
		}
		
		$max = $this->getInjector()->getDB()->query("SELECT COUNT(*) AS `max` FROM " . $table);
		
		if(!$max) {
			trigger_error('Couldn\'t fetch data for webstats! Please contact the administrator!', E_USER_WARNING);
			return;
		}
		
		foreach($max as $row) {
			$this->maxPage = floor($row['max']/$this->perpage);
		}
		
		foreach($result as $row) {
			$this->stats[] = new BedwarsPlayerStats($row);
		}
	}

	/**
	 * Loading every parameter and finally the stats
	 */
	private function load()
	{
		try  {
			$inj = $this->getInjector();
			
			$this->path = $this->getInjector()->getPath();
			
			$this->perpage = $inj->getConfig()['per-page'];
			$this->page = $this->loadPage();
			$this->order = (isset($_GET['bw-order'])) ? $_GET['bw-order'] : $inj->getConfig()['order'];
			$this->orderDirection = strtoupper((isset($_GET['bw-direction'])) ? $_GET['bw-direction'] : $inj->getConfig()['direction']);
			$this->search = (isset($_GET['bw-search'])) ? $_GET['bw-search'] : null;
			
			if($this->perpage <= 0) {
				$this->perpage = 10;
			}
			
			if($this->orderDirection != 'ASC'
					&& $this->orderDirection != 'DESC') {
				$this->orderDirection = 'ASC';
			}
	
			if(!in_array($this->order, BedwarsPlayerStats::$COLUMNS)) {
				$this->order = $inj->getConfig()['order'];
			}
	
			$this->loadStats();
		} catch(Exception $ex) {
			trigger_error('#' . $ex->getCode() . ' Error in loading bedwars webstats: ' . $ex->getMessage());
		}
	}
	
	/**
	 * Returns the errors (as array) which were generated
	 * 
	 * @return multitype:string
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 * Returns the current stats
	 * @return multitype:string,int
	 */
	public function getStats()
	{
		return $this->stats;
	}
	
	
	/**
	 * Returns the current order field
	 * @return string the field which is ordered by
	 */
	public function getCurrentOrder()
	{
		return $this->order;
	}
	
	/**
	 * Returns the current order direction
	 * @return string current order direction
	 */
	public function getCurrentOrderDirection()
	{
		return $this->orderDirection;
	}
	
	/**
	 * Returns the current displaying page
	 * @return number
	 */
	public function getPage()
	{
		return intval($this->page);
	}
	
	/**
	 * Returns the number how much items are displayed per page
	 * @return number
	 */
	public function getPerPage()
	{
		return intval($this->perpage);
	}
	
	/**
	 * Returns the current search string
	 * @return string	 
	 */
	public function getCurrentSearch()
	{
		return htmlspecialchars($this->search);
	}
	
	/**
	 * Return the maximum pages available
	 * @return number
	 */
	public function getMaxPage()
	{
		return $this->maxPage;
	}
	
	private function getFilteredUrl($url)
	{
		$bwParams = array(
			'bw-page',
			'bw-order',
			'bw-direction',
			'bw-search'
		);
		
		$parts = explode('?', $url);
		if(count($parts) === 1) {
			return $parts[0];
		}
		
		$fullUrl = array_shift($parts);
		$parts = explode('&', $parts[1]);
		$params = '';
		foreach($parts as $part) {
			if(!in_array($part, $bwParams)) {
				if($params == '') {
					$params = '?' . $part;
				} else {
					$params .= '&' . $part;
				}
			}
		}
		
		return $fullUrl . $params;
	}

	/**
	 * Easy output of the whole webstats
	 * prepared by the webstats api
	 * 
	 * @param string $path The path where the view is displaying
	 * @param boolean $withJs Include Javascript-Resources
	 */
	public function view($withJs = true)
	{
		$this->path = $this->getFilteredUrl($_SERVER['REQUEST_URI']);
		
		$this->getInjector()->getRouter()->display('stats', $this);
		
		if($withJs) {
			$this->getInjector()->getRouter()->display('js');
		}
	}
	
	/**
	 * Returns the current webstats path
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}
	
	/**
	 * Displays the needed css ressources
	 */
	public function displayCss()
	{
		$this->getInjector()->getRouter()->display('css');
	}
	
	/**
	 * Displays the needed js ressources
	 */
	public function displayJs()
	{
		$this->getInjector()->getRouter()->display('js');
	}
	
}

?>