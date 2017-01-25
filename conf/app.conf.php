<?php 	// app.conf.php

	class Config {

		/* Database type */
		private static $db_type = 'MySQL';

		/* Database configuration */
		private static $db = [
			'host' => 'localhost',
			'port' => '3306',
			'user' => 'username',
			'pass' => 'password',
			'db'   => 'database'
		];

		/* To use 'Production' environment or 'Development' */
		private static $env = 'Development';

		/* Layout to use */
		private static $layout = 'application';

		/* Handle errors via controller or static pages? 
		   Note: This controller is not included in the routing */
		private static $error_controller = True;
		
		/* If static pages are being used provide their location */
		private static $error_pages = [
			'404' 	=> '/app/errors/404.html'
		];


		/* Getters for the above variables */
		public static function db_type() {
			return self::$db_type;
		}

		public static function db() {
			return self::$db;
		}

		public static function env() {
			return self::$env;
		}

		public static function is_dev() {
			return (self::$env == 'Development');
		}

		public static function layout() {
			return self::$layout;
		}

		public static function use_error_controller() {
			return self::$error_controller;
		}

		public static function error_pages() {
			return self::$error_pages;
		}

	}

?>