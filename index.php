<?php 	// index.php, handles all requests

	/* GMT best timezone */
	date_default_timezone_set("Etc/GMT");

	/* Contants */
	define('SITE_ROOT', 	realpath(dirname(__FILE__)));
	define('CONFIG_ROOT', 	realpath(SITE_ROOT . "/conf"));
	define('APP_ROOT', 		realpath(SITE_ROOT . "/app"));
	define('LIB_ROOT',		realpath(SITE_ROOT . "/lib"));
	define('VERSION', 		"v0.0.2");
	define('F_SEP', DIRECTORY_SEPARATOR);

	/* Handle the request */
	$request = trim(strtok($_SERVER["REQUEST_URI"],'?'), '/');

	require (CONFIG_ROOT . F_SEP . "/app.conf.php");
	require (CONFIG_ROOT . F_SEP . "/routing.conf.php");
	include (LIB_ROOT . F_SEP . "global_utils.php");
	

	Routing::get_route($request);

	/* No Route Found, error out. */
	if ( !isset( $GLOBALS['app_controller'] ) ) {

		Errors::generate_error('404');
		exit;

	} else {

		$controller = realpath(APP_ROOT . F_SEP . "controllers" . F_SEP . "{$GLOBALS['app_controller']}.php");
		$controller_utils = realpath(APP_ROOT . F_SEP . "utils" . F_SEP . "{$GLOBALS['app_controller']}.php");

		if (!file_exists($controller) || is_dir($controller)) {

			Errors::generate_error('500', "The requested controller '{$GLOBALS['app_controller']}' does not exist.");

		} else {

			if (file_exists($controller_utils) && !is_dir($controller_utils)) {
				include $controller_utils;
			}

			require $controller;

		}

	}

?>
