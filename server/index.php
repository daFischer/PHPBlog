<?php
// This file handles all requests

require 'config.php';
require 'UriHandler.php';

// Read the URI that was used by the browser = '/'.$folder
$uri = substr($_SERVER["REQUEST_URI"], strlen($folder) + 1);
// Remove set variables from the URI (they're not needed here)
$uri_end = strpos($uri, "?");
if ($uri_end !== false)
	$uri = substr($uri, 0, $uri_end);

// Is there a new post that can be added to the DB?
if (file_exists("entries/new") && file_exists("entries/new/title.txt") &&!$testing) {

	// Find out the name and delete the file containing it
	$name = file_get_contents("entries/new/title.txt");
	unlink("entries/new/title.txt");

	// Insert the new post to the DB
	require_once("database/CacheLoader.php");
	$db = CacheLoader::getInstance();
	$newId = $db->insertPost($name);

	// Rename the folder containing the post's files to its id
	rename("entries/new", "entries/" . $newId);
}

handleUri($uri);

?>