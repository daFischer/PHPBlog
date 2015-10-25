<?php
require_once("LoaderInterface.php");
/**
*  
*/
class FileLoader implements LoaderInterface
{
	function __construct()
	{

	}
	
	public function loadPage($amount, $page)
	{
		if (!$page)
			$page = 1;

		$dirs = self::getDirs();
		// remove newest post, since it's still in testing
		array_shift($dirs);
		// Also remove enough posts that only one page full (10) are left
		while (sizeof($dirs) > 10)
			array_pop($dirs);

		return self::loadPosts($dirs);
	}
	public function loadDebugPost()
	{
		$posts = array();
		if (file_exists("./entries/new/title.txt"))
			$posts[] = array(
				'index' => "new",
				'title' => file_get_contents("./entries/new/title.txt"),
				'date' => date("j\. M\. y"),
				'html' => file_get_contents("./entries/new/Post.html"),
				'js' => file_get_contents("./entries/new/Post.js"),
				'css' => file_get_contents("./entries/new/Post.css"));
		$posts[] = array(
			'index' => 0,
			'title' => "Old",
			'date' => "13. Jan. 37",
			'html' => file_get_contents("./entries/0/Post.html"),
			'js' => file_get_contents("./entries/0/Post.js"),
			'css' => file_get_contents("./entries/0/Post.css"));

		return $posts;
	}
	public function loadPosts($indices, $debugging = false)
	{
		$posts = array();
		for ($i = 0; $i < sizeof($indices); $i++) {
			$id = intval($indices[$i]);
			$files = glob("./entries/".$id."/*.html");
			if (!$files || (sizeof($files) == 0)) {
				continue;
			}
			$title = substr($files[0], strlen("./entries/".$id."/"), -5);
			if ($debugging)
				$date = date("j\. M\. y");
			else
				$date = self::creationDate("entries/".$id);
			$js = "";
			if (file_exists("./entries/".$id."/".$title.".js"))
				$js = file_get_contents("./entries/".$id."/".$title.".js");
			$css = "";
			if (file_exists("./entries/".$id."/".$title.".css"))
				$css = file_get_contents("./entries/".$id."/".$title.".css");
			$posts[] = array(
				'index' => $id,
				'title' => $title,
				'date' => $date,
				'html' => file_get_contents("./entries/".$id."/".$title.".html"),
				'js' => $js,
				'css' => $css);
		}
		return $posts;
	}
	public function searchPosts($tags)
	{
		$dirs = self::getDirs();
		$posts = array();
		for ($i = 0; $i < sizeof($dirs); $i++) { 
			$postHtml = file_get_contents($dirs[$i]);
			for ($j = 0; $j < sizeof($tags); $j++) { 
				if (strpos($postHtml, $tags[$j]) == false)
					break;
				if($j == sizeof($tags) - 1) {
					$posts[] = intval($dirs[$i]);
				}
			}
		}
		return $posts;
	}

	private function getDirs()
	{
		// Only use directories for entries that have their index as folder name
		// and don't load 0, since that's a test post
		function isDir($a) {
			return is_dir("./entries/".$a) && ((intval($a) != 0));
		}
		$dirs = array_values(array_filter(scandir("./entries"), "isDir"));
		// sort all posts, putting the newest posts in the front
		function sortPosts($a, $b) {
			return intval($b) - intval($a);
		}
		usort($dirs, "sortPosts");
		return $dirs;
	}
	private function creationDate($path)
	{
		if(file_exists($path."/publish.time")) {
			$date = file_get_contents($path."/publish.time");
			return $date;
		}
		else {
			$date = date("j\. M\. y");
			$myfile = fopen($path."/publish.time", "w");
			if ($myfile) {
				fwrite($myfile, $date);
				fclose($myfile); 
			}
			return $date;
		}
	}
}


?>