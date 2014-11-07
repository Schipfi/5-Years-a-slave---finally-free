<?php
	if(isset($page) || isset($_GET['page']))
	{
		if(!isset($page))
			$page = $_GET['page'];

		$extensions = array('.spa', '.dpa', '.tpa');
		if(!defined('CONFIG_LOADED'))
			require_once('../config/global.cfg');

		if(preg_match("#^[0-9a-z/]+#", $page))
		{
			$match = false;

			foreach($extensions as $extension)
			{
				if(file_exists(PAGES_DIR . $page . $extension))
				{
					require(PAGES_DIR . $page . $extension);
					$match = true;
					break;
				}
			}

			if(!$match)
			{
				http_response_code(404);
				include(PAGES_DIR . 'errors/404.spa');
			}
		}
		else
		{
			die();
		}
	}
?>