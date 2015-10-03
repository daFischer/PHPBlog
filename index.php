<?php
// This file handles all requests
// TODO: It might be a good idea to redirect e.g. css file requests differently
//  	in the .htaccess file

// Read the URI that was used by the browser
$uri = substr($_SERVER[REQUEST_URI], 6);
// Remove set variables from the URI (they're not needed here)
$uri_end = strstr($uri, '?');
if ($uri_end >= 0)
	$uri = substr($uri, 0, $uri_end);

if (preg_match("/^[a-zA-Z]+\.css$/", $uri)) {

	// A css file has been recognized
	readfile("css/".$uri);
}
else if ($uri == "") {

	// Since after '/blog/' there was nothing in the uri, return the index document
	//require("index.phtml");
	require_once("PageBuilder.php");
	$pc = new PageBuilder();
	$pc->frontpage();
	$pc->printOut();
}
else {

	// The requested file is not (yet) accessible, so throw a 404 error
	require("errors/404.php");
}
?>