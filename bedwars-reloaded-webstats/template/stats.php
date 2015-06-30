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
?>
<div class="row">
	<div class="col-md-offset-6 col-md-6">
		<form method="GET" action="<?php echo $this->getWebstats()->getPath(); ?>" class="form-inline pull-right">
			<div class="form-group">
			    <input type="text" class="form-control" id="bw-search" name="bw-search" placeholder="<?php echo $this->getInjector()->_('search.placeholder'); ?>">
		    </div>
			<div class="form-group">
		    	<button type="submit" class="btn btn-default"><?php echo $this->getInjector()->_('search.button'); ?></button>
		    </div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped">
			<tr>
				<?php foreach(BedwarsPlayerStats::$COLUMNS as $column): ?>
					<?php if($column == 'uuid') continue; ?>
					<th><?php echo $this->getInjector()->_('stats.column.' . $column); ?></th>
				<?php endforeach; ?>
			</tr>
			<?php foreach($this->getWebstats()->getStats() as $stats): ?>
				<tr>
					<?php foreach(BedwarsPlayerStats::$COLUMNS as $column): ?>
						<?php if($column == 'score'): ?>
							<td><strong><?php echo $stats->$column; ?></strong></td>
						<?php elseif($column == 'kd'): ?>
							<td><?php echo round($stats->kd, 2); ?></td>
						<?php elseif($column != 'uuid'): ?>
							<td><?php echo $stats->$column; ?></td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>