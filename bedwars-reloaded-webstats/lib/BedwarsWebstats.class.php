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
 * BedwarsWebstats.class.php
 */

/**
 * Represents the whole webstats (Webstats dependency) 
 * (more or less the bedwars webstats table)
 * 
 * @author Yannici
 *
 */
class BedwarsWebstats extends BedwarsDependency
{
	/**
	 * Returns the tablename of this database table
	 * 
	 * @var string
	 */
	const DB_TABLENAME = 'stats_players';
	
	/**
	 * Get-Parameter: bw-page  
	 * The current page of the bedwars webstats
	 * 
	 * @var int
	 */
	private $page = null;
	
	/**
	 * The pages the webstats have
	 * 
	 * @var int
	 */
	private $maxPage = null;
	
	/**
	 * Config-Parameter: per-page  
	 * The stats per page
	 * 
	 * @var int
	 */
	private $perpage = null;
	
	/**
	 * Get-Parameter: bw-order  
	 * The column which is ordered by
	 * 
	 * @var string
	 */
	private $order = null;
	
	/**
	 * Get-Parameter: bw-direction  
	 * The direction of the order (_ASC_ or _DESC_)
	 * 
	 * @var string
	 */
	private $orderDirection = null;
	
	/**
	 * Get-Parameter: bw-search  
	 * The search parameter which is searched for
	 * 
	 * @var string
	 */
	private $search = null;
	
	/**
	 * The current path where the webstats are displayed  
	 * This is used for every url on the webstats template
	 * 
	 * @var string
	 */
	private $path = null;

	/**
	 * The full stats information is stored here
	 * 
	 * @var array
	 */
	private $stats = array();
	
	/**
	 * The errors will be stored in this member
	 * 
	 * @var array
	 */
	private $errors = array();

	/**
	 * Initialize a new instance of the BedwarsWebstats-class
	 * and loads the webstats which will be displayed on the current webstats-page
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load();
	}
	
	/**
	 * Calculate the current page with help of the _GET-Parameter_
	 * 
	 * @return int
	 */
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
	
	/**
	 * Loads the stats from the database with help of the database
	 * connection stored in the injector instance
	 * 
	 * @return void
	 */
	private function loadStats()
	{
		$table = BedwarsWebstatsInjector::DB_PREFIX . self::DB_TABLENAME;
		
		$pageStart = ($this->perpage * $this->page) - $this->perpage;
		if($pageStart <= 0) {
			$pageStart = 0;
		}
		
		$stmt = null;
		$result = [];
		$defaultOrder = $this->getInjector()->getConfig()['order'];
		$rank = '(SELECT COUNT(*)+1 FROM ' . $table . ' WHERE ' . $defaultOrder . ' >  bw.' . $defaultOrder . ') AS rank';

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
	 * Loading every needed parameter and finally the stats
	 * 
	 * @return void
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
	 * @return string
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 * Returns the current stats as multi-dimensional array
	 * 
	 * @return array
	 */
	public function getStats()
	{
		return $this->stats;
	}
	
	/**
	 * Returns the current order field
	 * 
	 * @return string The field which is ordered by
	 */
	public function getCurrentOrder()
	{
		return $this->order;
	}
	
	/**
	 * Returns the current order direction
	 * @return string Current order direction
	 */
	public function getCurrentOrderDirection()
	{
		return $this->orderDirection;
	}
	
	/**
	 * Returns the current displaying page
	 * 
	 * @return int
	 */
	public function getPage()
	{
		return intval($this->page);
	}
	
	/**
	 * Returns the number how much items are displayed per page
	 * 
	 * @return int
	 */
	public function getPerPage()
	{
		return intval($this->perpage);
	}
	
	/**
	 * Returns the current search string
	 * 
	 * @return string	 
	 */
	public function getCurrentSearch()
	{
		return htmlspecialchars($this->search);
	}
	
	/**
	 * Return the maximum pages available
	 * 
	 * @return int
	 */
	public function getMaxPage()
	{
		return $this->maxPage;
	}
	
	/**
	 * Filters the _GET-Parameter_ which are used by the webstats 
	 * and leave the other GET-Parameter in the url
	 * 
	 * @param string $url The url which will be filtered
	 * 
	 * @return string The filtered URL
	 */
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
		$parts = explode('&', $parts[0]);
		$params = '';
		foreach($parts as $part) {
			$getName = explode('=', $part);
			$getName = $getName[0];
			
			if(!in_array($getName, $bwParams)) {
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
	 * @param boolean $withJs Include Javascript-Resources
	 * 
	 * @return void
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
	 * 
	 * @return void
	 */
	public function displayCss()
	{
		$this->getInjector()->getRouter()->display('css');
	}
	
	/**
	 * Displays the needed js ressources
	 * 
	 * @return void
	 */
	public function displayJs()
	{
		$this->getInjector()->getRouter()->display('js');
	}
	
}

?>