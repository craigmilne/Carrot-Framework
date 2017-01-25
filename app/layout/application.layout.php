<?php 	// application.layout.php

	$title = ucwords($args['app_view']);
	if (isset($args['title'])) {
		$title = $args['title'];
	}

?>

<html>
	<head>
		<title><?php echo $title; ?></title>
	</head>
	<body>

<?php
	include $args['app_view_path'];
?>

	</body>
</html>

