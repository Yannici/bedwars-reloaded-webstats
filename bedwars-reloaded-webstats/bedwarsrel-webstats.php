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

// Add class-autoloader
function autoload($class) {
	require_once('./lib/' . $class . '.class.php');
}

// register autoloader
spl_autoload_register('autoload', false);

// require config
require_once('includes/config.inc.php');

// require texts
require_once('includes/texts.inc.php');

$injector = new BedwarsWebstatsInjector($config, $texts);
$injector->initialize();
?>