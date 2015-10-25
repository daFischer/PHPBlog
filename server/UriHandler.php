<?php

function handleUri($uri)
{
	require 'config.php';

	if (preg_match("/^css\/[a-zA-Z]+\.css$/", $uri)) {

		// A css file has been recognized
		header("Content-Type: text/css");
		header("X-Content-Type-Options: nosniff");
		readfile($uri);
	}
	else if (preg_match("/^entries\/[0-9]+\/([a-zA-Z0-9][\/]?)+\.[A-Za-z]{1,4}$/", $uri)) {

		// A file uploaded with a post should be loaded
		if(file_exists($uri))
		readfile($uri);
	}
	else
	{
		require_once("PageBuilder.php");
		if ($testing) {
			require_once("database/FileLoader.php");
			$pc = new PageBuilder(new FileLoader());
		} else {
			require_once("database/CacheLoader.php");
			$pc = new PageBuilder(CacheLoader::getInstance());
		}
		if ($uri == "") {

			// Since after '/blogfolder/' there was nothing in the uri, return the frontpage
			$page = $_GET["p"];
			if (!$page)
				$page = 1;

			$pc->frontpage($page);
			$pc->printOut();
		}
		else if ($uri == "test" && $testing) {

			$pc->debugPost();
			$pc->printOut();
		}
		else if ($uri == "post") {

			$id = max(1, intval($_GET["id"]));
			$pc->singlePost($id);
			$pc->printOut();
		}
		else {

			// The requested file is not (yet) accessible, so throw a 404 error
			require("errors/404.php");
		}
	}
}

?>