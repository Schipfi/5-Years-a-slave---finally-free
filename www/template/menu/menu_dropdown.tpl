<li class="dropdown<?php echo containsActive($items); ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $name; ?> <span class="caret"></span></a>
	<ul class="dropdown-menu" role="menu">
	<?php
		foreach($items as $subitem)
		{
			$key = $subitem[0];
			$name = $subitem[1];
			include(TEMPLATE_DIR . 'menu/menu_item.tpl');
		}
	?>
	</ul>
</li>