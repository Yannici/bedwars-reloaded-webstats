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
		<form method="GET" action="<?php echo $this->getPath(); ?>" class="form-inline pull-right">
			<div class="form-group">
			    <input type="text" class="form-control" id="bw-search" name="bw-search" placeholder="<?php echo $this->getInjector()->_('search.placeholder'); ?>">
		    </div>
			<div class="form-group">
		    	<button type="submit" class="btn btn-default"><?php echo $this->getInjector()->_('search.button'); ?></button>
		    </div>
		    <?php if($this->isFiltered()): ?>
		    	<div class="form-group">
			    	<a href="<?php echo $this->getPath(); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
			    </div>
		    <?php endif; ?>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped">
			<tr>
				<th><?php echo $this->getInjector()->_('stats.column.rank'); ?></th>
				<?php foreach(BedwarsPlayerStats::$COLUMNS as $column): ?>
					<?php if($column == 'uuid') continue; ?>
					<th>
						<a href="<?php echo $this->getPath(); ?>?bw-order=<?php echo $column; ?>&bw-direction=<?php echo ($this->getWebstats()->getCurrentOrderDirection() == 'DESC') ? 'ASC' : 'DESC'; ?>"><?php echo $this->getInjector()->_('stats.column.' . $column); ?></a>
						<?php if($this->getWebstats()->getCurrentOrder() == $column): ?>
							<?php if($this->getWebstats()->getCurrentOrderDirection() == 'DESC'): ?>
								<span class="glyphicon glyphicon-arrow-down"></span>
							<?php else: ?>
								<span class="glyphicon glyphicon-arrow-up"></span>
							<?php endif; 
						endif; ?>
					</th>
				<?php endforeach; ?>
			</tr>
			<?php foreach($this->getWebstats()->getStats() as $stats): ?>
				<tr>
					<td><?php echo '<strong>' . $stats['rank'] . '</strong>'; ?></td>
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
<div class="row">
	<div class="col-md-12">
		<nav>
			<ul class="pager">
				<?php 
				$previousDisabled = ($this->getWebstats()->getPage() <= 1);
				$nextDisabled = ($this->getWebstats()->getPage() >= $this->getWebstats()->getMaxPage() || $this->getWebstats()->getMaxPage() == 1);
				?>
				<li class="previous <?php echo ($previousDisabled) ? 'disabled' : ''; ?>">
					<a href="<?php echo ($previousDisabled) ? 'javascript:;' : $this->getPath() . '?bw-page=' . ($this->getWebstats()->getPage()-1); ?>"><?php echo $this->getInjector()->_('pager.previous'); ?></a>
				</li>
				<li class="next <?php echo ($nextDisabled) ? 'disabled' : ''; ?>">
					<a href="<?php echo ($nextDisabled) ? 'javascript:;' : $this->getPath() . '?bw-page=' . ($this->getWebstats()->getPage()+1); ?>"><?php echo $this->getInjector()->_('pager.next'); ?></a>
				</li>
			</ul>
		</nav>
	</div>
</div>