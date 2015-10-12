<?php
// This file handles all requests

include 'config.php';

// Read the URI that was used by the browser = '/'.$folder
$uri = substr($_SERVER[REQUEST_URI], strlen($folder) + 1);
// Remove set variables from the URI (they're not needed here)
$uri_end = strpos($uri, '?');
if ($uri_end !== false)
	$uri = substr($uri, 0, $uri_end);

if (preg_match("/^[a-zA-Z]+\.css$/", $uri)) {

	// A css file has been recognized
	header("Content-Type: text/css");
	header("X-Content-Type-Options: nosniff");
	readfile("css/".$uri);
}
else
{
	require_once("PageBuilder.php");
	require_once("database/DBLoader.php");
	if ($uri == "") {

		// Since after '/blogfolder/' there was nothing in the uri, return the frontpage
		$page = $_GET["p"];
		if (!$page)
			$page = 1;

		$pc = new PageBuilder(new DBLoader());
		$pc->frontpage($page);
		$pc->printOut();
	}
	/*else if ($uri == "test") {

		$pc = new PageBuilder(new DBLoader());
		$pc->debugPost();
		$pc->printOut();
	}*/
	else if ($uri == "post") {

		$id = max(1, intval($_GET["id"]));
		$pc = new PageBuilder(new DBLoader());
		$pc->singlePost($id);
		$pc->printOut();
	}
	else if (preg_match("/^entries\/[0-9]+\/([a-zA-Z0-9][\/]?)+\.[A-Za-z]{1,4}$/", $uri)) {

		// A file uploaded with a post should be loaded
		if(file_exists($uri))
		readfile($uri);
	}
	else {

		// The requested file is not (yet) accessible, so throw a 404 error
		require("errors/404.php");
	}
}
?>