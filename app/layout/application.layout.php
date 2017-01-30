<?php 	// application.layout.php

	$title = ucwords($args['app_view']);
	if (isset($args['title'])) {
		$title = $args['title'];
	}

?>

<html>
	<head>
		<title><?php echo $title; ?></title>
		<?php

		if (Config::is_dev()) {
			require LIB_ROOT . "/less/lessc.inc.php";

			$less = new lessc;
			echo "<style>";
			echo $less->compileFile(realpath(APP_ROOT . "/content/css/style.less"));
			echo "</style>";
		}

		?>
	</head>
	<body>

<?php
	include $args['app_view_path'];
?>

	</body>
</html>

