<?php
    /* Start Accessed Directly */
		// Exit if accessed directly
		if(!defined('ABSPATH')){
			exit;
		}
	/* End Accessed Directly */
?>

<?php
	if (isset($_GET['viewcourse']) != "yes") {
		require_once dirname(__DIR__) . "/backendpages/includes/coursespage/courses.php";
	} else {
		require_once dirname(__DIR__) . "/backendpages/includes/coursespage/singlecourse.php";
	}
?>