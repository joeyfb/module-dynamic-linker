<?php
/* Below is code required for redirects.
 * Move this to your redirect location and remove commenting.

require_once('../app/core/sleepy.php');

if (isset($_GET["id"]) && ! empty($_GET["id"])) {
	// If ID is set and not empty, look up page based on ID
	// If Dyli doesn't find the link, it will return /404
	$href = Dyli::href( $_GET["id"] );

	header("Location: $href");

} else {
	// If ID is not set go to 404 page
	header("Location: /404.php");

}

*/