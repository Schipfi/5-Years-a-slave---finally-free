<?php
	//ob_start('ob_tidyhandler');

	$page = "index";
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}

	require_once('config/global.cfg');
	require_once('config/menu/menu.cfg');
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<base href="<?php echo WEBROOT; ?>" />
		<noscript><meta http-equiv="refresh" content="0; url=nojs" /></noscript>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/qr.js"></script>
		<script type="text/javascript" src="js/fam.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/fam.css" />
		<title>Eventmanager</title>
	</head>
	<body>
		<nav id="navigation" class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav">
						<span class="sr-only">Navigation umschalten</span>
						<span class="glyphicon glyphicon-list"></span>
					</button>
					<a class="navbar-brand" href="."><span class="logo glyphicon glyphicon-calendar"></span> Eventmanager</a>
				</div>

				<div id="topnav" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
					<?php
						handleMenu($menu, 'top', 'left');
					?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					<?php
						handleMenu($menu, 'top', 'right');
					?>
					</ul>
				</div>
			</div>
		</nav>

		<div id="page" class="container">
			<?php require_once(PAGES_DIR . 'handler.php'); ?>
		</div>
		<div id="loader" style="display: none">
				<img src="img/loading.svg" alt="Loading..." />
				<div class="slow" style="display: none">This might take a moment...</div>
		</div>

		<nav id="footer" class="navbar navbar-default navbar-fixed-bottom" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bottomnav">
						<span class="sr-only">Navigation umschalten</span>
						<span class="glyphicon glyphicon-list"></span>
					</button>
				</div>
				<div id="bottomnav" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
					<?php
						handleMenu($menu, 'bottom', 'left');
					?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					<?php
						handleMenu($menu, 'bottom', 'right');
					?>
					</ul>
				</div>
			</div>
		</nav>
	</body>
</html>

<?php
	function handleMenu($menu, $selection, $position)
	{
		foreach($menu as $item)
		{
			if($item[0] == $selection)
			{
				if($item[1] == $position)
				{
					$key = $item[2];
					$icon = $item[3];
					$name = $item[4];

					if($item[5] == null)
					{
						if($key != '')
						{
							if($icon == null)
								include(TEMPLATE_DIR . 'menu/menu_item.tpl');
							else
								include(TEMPLATE_DIR . 'menu/menu_iconitem.tpl');
						}
						else
							include(TEMPLATE_DIR . 'menu/menu_label.tpl');
					}
					else
					{
						$items = $item[5];
						include(TEMPLATE_DIR . 'menu/menu_dropdown.tpl');
					}
				}
			}
		}
	}
	function isActive($location)
	{
		global $page;

		if($location == $page)
			return ' active';
		else
			return '';
	}
	function containsActive($items)
	{
		global $page;
		
		foreach($items as $item)
		{
			if($item[0] == $page)
				return ' active';
			else
				return '';
		}
	}
?>