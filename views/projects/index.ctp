<div class="projects index">
<?php foreach ((array)$projects as $project):?>
	
	<div class="project">
		
		<h3 class="name">
			<?php 
				echo $html->link($project['Project']['name'], array(
					'admin' => false,
					'controller' => 'wiki', 'action' => 'index', 
				));?>
		</h3>
		
		<p class="description">
			<?php echo $project['Project']['description'];?>
		</p>
		
	</div>
	
<?php endforeach;?>

<?php
	echo $paginator->prev();
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next();
?>

</div>