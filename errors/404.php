<?php
header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";
echo "The page '$_SERVER[REQUEST_URI]' that you have requested could not be found.\n";
echo "Path is ".$_GET['path'];
exit();
?>