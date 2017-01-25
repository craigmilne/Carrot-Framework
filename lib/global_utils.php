<?php 	// global_utils.php

	/* Serve a file as is */
	function serve_content($filename) {
		if (!file_exists($filename)) {
			Errors::generate_error('404', "File does not exist.");
		} else if (is_dir($filename)) {
			Errors::generate_error('403', "Path is a directory.");
		} else {
			// First get the file type
			$content_type = "";
			$finfo = new finfo();
			if (is_resource($finfo) === true) {
				$content_type = $finfo->file($filename, FILEINFO_MIME_TYPE);
			}

			header("Content-Type: {$content_type}");
			readfile($filename);
		}
		exit;
	}

	/* Render a layout */
	function render($controller, $view, $args=[]) {

		$layout = Config::layout();

		if ( isset($args['layout']) ) {
			$layout = $args['layout'];
		}

		$layout_path = realpath(APP_ROOT . F_SEP . "layout" . F_SEP . $layout . ".layout.php");

		if (!file_exists($layout_path) || is_dir($layout_path)) {
			Errors::generate_error('500', "The requested layout '{$layout}' could not be loaded.");
		} else {

			$args['app_controller'] = $controller;
			$args['app_view']		= $view;
			$args['app_view_path']	= realpath(APP_ROOT . F_SEP . "views" . F_SEP . "{$args['app_controller']}" . F_SEP . "{$args['app_view']}.php");

			// Verify that the view exists, if not then it will go to an error. If it is the error having issues then it displays a static error.
			if (file_exists($args['app_view_path']) && !is_dir($args['app_view_path'])) {
				require $layout_path;
			} else if ($args['app_controller'] != 'errors') {
				Errors::generate_error('500', "The requested view '{$args['app_view']}' could not be found.");
			} else {
				Errors::generate_error('500', "The requested view '{$args['app_view']}' could not be loaded. An error on an error page?!", True);
			}
		}

		exit;

	}

	/* A class containing a mini-controller for errors as a fallback. */
	class Errors {

		/* Generate an error and send to the error controller */
		public static function generate_error($error, $information = "", $force_static = False) {	

			if (Config::use_error_controller() && !$force_static) {

				$GLOBALS['app_controller'] = 'errors';
				$GLOBALS['app_action'] = $error;
				$GLOBALS['app_information'] = $information;

				require (APP_ROOT . F_SEP . "controllers" . F_SEP . "{$GLOBALS['app_controller']}.php");
				exit;

			} else {

				$error_pages = Config::error_pages();

				if ( isset($error_pages[$error]) ) {
					$path = realpath( SITE_ROOT . $error_pages[$error]);
					if (file_exists($path)) {
						serve_content( $path );
						exit;
					}
				}

				header('HTTP/1.1 ' . $error);
				switch ($error) {
					case '404':
						echo "
							<h1>404 Not Found</h1>
							<p>The requested page could not be found.</p>
						";
						break;
					case '403':
						echo "
							<h1>403 Forbidden</h1>
							<p>You do not have permission to view the requested page.</p>
						";
						break;
					default:
						echo "
							<h1>{$error} An Error Occurred</h1>
							<p>An unexpected error has occurred.</p>
						";
						break;
				}
				echo "
					<p><em>{$information}</em></p>
					<hr/>
					<p>Carrot " . VERSION . "</p>
				";
				exit;

			}

		}

	}

?>