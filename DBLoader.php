<?php

/**
*  
*/
class DBLoader
{
	static public function loadPosts($amount)
	{
		$page = $_GET["p"];
		if (!$page)
			$page = 1;

		// TODO: Query database

		// for testing, just return some default values
		$posts = array();
		$posts[0] = array(
					'index' => 1,
					'title' => "First",
					'date' => "3. Oct. 15",
					'html' => file_get_contents("./entries/1/First.html"),
					'js' => file_get_contents("./entries/1/First.js"),
					'css' => file_get_contents("./entries/1/First.css"));
		$posts[1] = array(
					'index' => 2,
					'title' => "Second",
					'date' => "4. Oct. 15",
					'html' => file_get_contents("./entries/2/Second.html"),
					'js' => file_get_contents("./entries/2/Second.js"),
					'css' => file_get_contents("./entries/2/Second.css"));
		return $posts;
	}
	
	/* *
	 * 
	 *
	static public function showPosts()
	{
		for ($i = 0; $i < sizeof(self::$posts); $i++)
		{
			if ($i != 0)
				echo file_get_contents("./Separator.html");
			$index = self::$posts[$i]['index'];
			$title = self::$posts[$i]['title'];
			$date = self::$posts[$i]['date'];
			$filename = "./entries/".$index."/".$title;
			if (!file_exists("./Post.html") || !file_exists($filename.".html"))
			{
				//echo " not found";
				continue;
			}
			$html = file_get_contents($filename.".html");
			$js = $css = "";
			if (file_exists($filename.".js"))
				$js = file_get_contents($filename.".js");
			if (file_exists($filename.".js"))
				$css = file_get_contents($filename.".css");
			$post = file_get_contents("./Post.html");
			$post = str_replace("[index]", $index, $post);
			$post = str_replace("[title]", $title, $post);
			$post = str_replace("[date]", $date, $post);
			$post = str_replace("[html]", $html, $post);
			echo $post;
		}
	}*/
}


?>