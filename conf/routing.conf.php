<?php 	// routing.conf.php

	class Routing {

		/* Add Routes Here */
		private static $get = [
			''				=> [ 'controller' => 'home', 	'action' => 'index' ],


			'content/(.*)'	=> [ 'controller' => 'content',	'action' => '{1}' ]
		];

		private static $post = [

		];

		/* Functions for querying routes. 
		   Uses '|' as a delimiter.		  */
		public static function get_route($request) {

			foreach (self::$get as $key => $data) {

				if (preg_match('|^' . $key . '$|', $request, $matches)) {
					foreach ($data as $type => $value) {
						for ($i = 0; $i < count($matches); $i++) {
							$value = str_replace('{' . $i . '}', $matches[$i], $value);
						}
						$GLOBALS["app_{$type}"] = $value;
					}
					return;
				}

			}

		}

		public static function post_route($request) {
			
			foreach (self::$post as $key => $data) {

				if (preg_match('|^' . $key . '$|', $request, $matches)) {
					foreach ($data as $type => $value) {
						for ($i = 0; $i < count($matches); $i++) {
							$value = str_replace('{' . $i . '}', $matches[$i], $value);
						}
						$GLOBALS["app_{$type}"] = $value;
					}
					return;
				}

			}

		}


	}

?>