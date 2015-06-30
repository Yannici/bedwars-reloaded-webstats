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

$config = array(
	'path' => 'http://localhost/webstats', // without slash
	'database' => array(
			'host' => 'localhost',
			'user' => 'root',
			'pw' => 'YOURPASSWORD',
			'db' => 'bedwars'
	),
	'per-page' => 10,
	'order' => 'score',
	'direction' => 'DESC' // ASC (ascending) or DESC (descending)
);

$texts = array(
	'stats.column.name' => 'Spielername',
	'stats.column.uuid' => 'UUID',
	'stats.column.kills' => 'Kills',
	'stats.column.deaths' => 'Tode',
	'stats.column.kd' => 'K/D',
	'stats.column.score' => 'Punkte',
	'stats.column.games' => 'Spiele',
	'stats.column.destroyedBeds' => 'zerstörte Betten',
	'stats.column.id' => 'ID',
	'stats.column.loses' => 'Verloren',
	'stats.column.wins' => 'Gewonnen',
	'search' => 'Suche',
	'search.placeholder' => 'Suchen ...',
	'search.button' => 'Suchen &raquo;'
);
?>