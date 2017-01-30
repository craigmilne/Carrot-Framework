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

	/* Database management, currently you are only allowed one database. */
	class Database {

		/* Query the database and return the output */
		public static function query($query) {
			$conn = self::open_conn();
			$result;
			if (Config::db_type() == "MySQL") {
				$result = $conn->query($query);
			} else {
				throw new Exception("Unsupported Database Type");
			}
			self::close_conn($conn);
			return $result;
		}

		/* Basic SQL functions, recommend you use 'query' for everything anyway */
		public static function select($cols, $table, $matches="1=1") {
			$query = "SELECT $cols FROM $table WHERE $matches";
			return self::query($query);
		}
		public static function insert_row($table, $values) {
			$query = "INSERT INTO $table (" . implode(",", array_keys($values)) . ") VALUES(" . implode(",", $values) . ")";
			return self::query($query);
		}
		public static function delete($table, $params) {
			$query = "DELETE FROM $table WHERE $params";
			return self::query($query);
		}
		public static function update($table, $set, $params) {
			$query = "UPDATE $table SET " . http_build_query($set,'',', ') . " WHERE " . http_build_query($params,'',',');
			return self::query($query);
		}
		public static function create_table($table_name, $rows) {
			$query = "CREATE TABLE $table_name (" . implode(", ", $rows) . ")";
			return self::query($query);
		}

		/* Same functions as above but we do sanitizing */
		public static function query_sanitize($query) {
			return self::query(self::sanitize($query));
		}
		public static function select_sanitize($cols, $table, $matches="1=1") {
			$query = self::sanitize("SELECT $cols FROM $table WHERE $matches");
			return self::query($query);
		}
		public static function insert_row_sanitize($table, $values) {
			$query = self::sanitize("INSERT INTO $table (" . implode(",", array_keys($values)) . ") VALUES(" . implode(",", $values) . ")");
			return self::query($query);
		}
		public static function delete_sanitize($table, $params) {
			$query = self::sanitize("DELETE FROM $table WHERE $params");
			return self::query($query);
		}
		public static function update_sanitize($table, $params) {
			$query = self::sanitize("UPDATE $table SET " . http_build_query($set,'',', ') . " WHERE " . http_build_query($params,'',','));
			return self::query($query);
		}

		/* Slightly more custom SQL stuff */
		public static function open_conn() {
			if (Config::db_type() == "MySQL") {
				$db = Config::db();
				$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['db'], $db['port']);
				if ($conn->connect_error) {
					throw new Exception("Failed to establish connection to database: " . $conn->connect_error);
				}
				// connected
				return $conn;	// pls close it if you open it
			} else {
				throw new Exception("Unsupported Database Type");
			}
		}
		public static function close_conn($conn) {
			if (Config::db_type() == "MySQL") {
				$conn->close();
				return;
			} else {
				throw new Exception("Unsupported Database Type");
			}
		}

		/* Clean a string for the database */
		public static function sanitize($string) {
			$string = strip_tags($string);
		    $string = htmlentities($string);
		    $string = stripslashes($string);
		    return mysql_real_escape_string($string);
		}

	}

?>