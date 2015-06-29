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
	
	private $page = null;
	private $perpage = null;
	private $order = null;
	private $orderDirection = null;
	private $search = null;

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
		$con = $this->getInjector()->getConnection();
		$table = BedwarsWebstatsInjector::DB_PREFIX . self::DB_TABLENAME;
		
		$pageStart = ($perpage * $page) - $perpage;
		
		$stmt = $con->query('SELECT * FROM ' . $table . ' ORDER BY ' . $this->order . ' ' . $this->orderDirection . ' LIMIT ' . $pageStart . ', ' . $this->perpage);
		if(!$stmt) {
			trigger_error('Couldn\'t fetch data for webstats! Please contact the administrator!', E_WARNING);
			return;
		}
		
		foreach($stmt as $row) {
			
		}
	}

	private function load()
	{
		$inj = $this->getInjector();
		
		$this->perpage = $inj->getConfig()['per-page'];
		$this->page = $this->loadPage();
		$this->order = (isset($_GET['bw-order'])) ? $_GET['bw-order'] : $inj->getConfig()['order'];
		$this->orderDirection = strtoupper((isset($_GET['bw-direction'])) ? $_GET['bw-direction'] : $inj->getConfig()['direction']);
		
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
	 * Easy output of the whole webstats
	 * prepared by the webstats api
	 * 
	 * @param boolean $withJs Include Javascript-Resources
	 * @param boolean $withCss Include CSS-Resources
	 */
	public function view($withJs = true, $withCss = true)
	{
		// Use output buffer to first collect any output
		ob_start();
		
		
		// output the buffer and end it
		ob_end_flush();
	}
	
	
	
}

?>