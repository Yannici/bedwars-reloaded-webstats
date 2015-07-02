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

require_once('bedwarsrel-webstats.php');

$webstats = new BedwarsWebstats()
?>
<!DOCTYPE html5>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Bedwars-Webstats :: Example</title>
		<?php $webstats->displayCss(); ?>
	</head>
	<body>
	<div class="container">
		<h1>Bedwars-Reloaded Webstats</h1>
		<?php $webstats->view(false); ?>
	</div>
	
	<?php $webstats->displayJs(); ?>
	</body>
</html>