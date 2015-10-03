<?php
$uri = substr($_SERVER[REQUEST_URI], 6);
if (preg_match("/^[a-zA-Z]+\.css$/", $uri))
{
	readfile("css/".$uri);
}
else if ($uri == "") {
	require("index.phtml");
}
else {
	require("errors/404.php");
}
?>