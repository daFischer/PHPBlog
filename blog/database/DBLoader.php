<?php
require_once("LoaderInterface.php");
require_once("/home/a1304351/blog/connect.php");
/**
*  
*/
class DBLoader implements LoaderInterface
{
	private $pdo;

	function __construct()
	{
		$this->pdo = pdoConnect();
	}

	public function loadPage($amount, $page)
	{
		$page -= 1; // Users think page 1 is the first one
		$statement = $this->pdo->prepare("
			SELECT * FROM Entry
			WHERE IsPublished
			ORDER BY Id DESC
			LIMIT :offset, :limit
			");
		$statement->bindValue(':limit', (int) $amount, PDO::PARAM_INT);
		$statement->bindValue(':offset', (int) ($page * $amount), PDO::PARAM_INT);
		//$statement->execute(array(':limit' => 2, ':offset' => $page));
		$statement->execute();
		$result = $statement->fetchAll();

		return self::openPosts($result);
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

	public function loadPosts($indices)
	{
		$placeholders = str_repeat ('?, ',  count ($indices) - 1) . '?';
		$statement = $this->pdo->prepare("
			SELECT * FROM Entry
			WHERE IsPublished AND
				Id IN ($placeholders)
			");
		$statement->execute($indices);
		$result = $statement->fetchAll();

		return self::openPosts($result);
	}

	private function openPosts($sqlResult)
	{
		$posts = array();
		for ($i = 0; $i < sizeof($sqlResult); $i++) {
			$id = $sqlResult[$i]["Id"];
			$title = $sqlResult[$i]["Name"];
			$date = date("j\. M\. y", strtotime($sqlResult[$i]["When"]));
			$js = "";
			if (file_exists("./entries/".$id."/Post.js"))
				$js = file_get_contents("./entries/".$id."/Post.js");
			$css = "";
			if (file_exists("./entries/".$id."/Post.css"))
				$css = file_get_contents("./entries/".$id."/Post.css");
			$posts[] = array(
				'index' => $id,
				'title' => $title,
				'date' => $date,
				'html' => file_get_contents("./entries/".$id."/Post.html"),
				'js' => $js,
				'css' => $css);
		}
		return $posts;
	}
}


?>