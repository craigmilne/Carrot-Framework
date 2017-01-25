<?php 	// errors.php - errors controller

	

	switch ($GLOBALS['app_action']) {
		case '404':
			render("errors", "404", [ 'app_information' => $GLOBALS['app_information'] ]);
			break;
		case '403':
			render("errors", "403", [ 'app_information' => $GLOBALS['app_information'] ]);
			break;
		default:
			render("errors", "error", [ 'app_information' => $GLOBALS['app_information'] ]);
			break;
	}

?>