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
 * BedwarsDatabase.class.php
 */

/**
 * This class handles the database connection.
 * 
 * @author Yannici
 * 
 */
class BedwarsDatabase
{
	
	/**
	 * The pdo database connection
	 * 
	 * @var PDO the pdo database conncetion
	 */
	private $connection = null;
	
	/**
	 * Database connection host
	 * 
	 * @var string The host of the database connection
	 */
	private $host = null;
	
	/**
	 * The database user which will be used
	 * 
	 * @var string database user
	 */
	private $user = null;
	
	/**
	 * The database with the stats-table in it
	 * 
	 * @var string database name
	 */
	private $db = null;
	
	/**
	 * Password for the database user
	 * 
	 * @var string database password
	 */
	private $pw = null;
	
	/**
	 * The charset which should be used in the database connection
	 * 
	 * @var string database connection charset
	 */
	private $charset = null;
	
	/**
	 * Is true when an error occured on initialize
	 * 
	 * @var boolean had initialize error
	 */
	private $initializeError = false;
	
	/**
	 * Initialize a new instance of the BedwarsDatabase class
	 * 
	 * @param array $dbConfig The database connection settings
	 * 
	 * @return void
	 */
	public function __construct($dbConfig)
	{
		if(!isset($dbConfig['host']) 
				|| !isset($dbConfig['user']) 
				|| !isset($dbConfig['db']) 
				|| !isset($dbConfig['pw'])) {
			trigger_error('Couldn\'t initialize database object! Please check your config!', E_USER_WARNING);
			$this->initializeError = true;
			return;
		}
		
		$this->host = $dbConfig['host'];
		$this->user = $dbConfig['user'];
		$this->db = $dbConfig['db'];
		$this->pw = $dbConfig['pw'];
		$this->charset = (isset($dbConfig['charset'])) ? $dbConfig['charset'] : 'utf8';
	}
	
	/**
	 * Closes the connection to the database
	 * 
	 * @return void
	 */
	public function close()
	{
		$this->connection = null;
	}
	
	/**
	 * Opens the database connection or displays 
	 * an error if something went wrong
	 * 
	 * @return void
	 */
	public function open()
	{
		if($this->initializeError) {
			throw new BadMethodCallException('The BedwarsDatabase instance wasn\'t initialized succesful. 
					You can\'t open a database connection!');
		}
		
		try {
			// Create new PDO connection
			$this->connection = new PDO(
					'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=' . $this->charset,
					$this->user,
					$this->pw, array(
							PDO::ATTR_EMULATE_PREPARES => false,
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_PERSISTENT => true // connection pooling
					));
		} catch(PDOException $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Executes a SQL and returns the statement
	 * 
	 * @param string $sql The _SQL_ which should be executed
	 * 
	 * @return PDOStatement 
	 */
	public function query($sql)
	{
		return $this->connection->query($sql);
	}
	
	/**
	 * Prepares a statement for executing
	 * 
	 * @param string $statement The statement (_SQL_)
	 * 
	 * @return PDOStatement
	 */
	public function prepare($statement) 
	{
		return $this->connection->prepare($statement);
	}
	
	/**
	 * Returns the database connection
	 * 
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->connection;
	}

}

?>