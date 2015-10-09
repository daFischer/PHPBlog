<?php
/**
*  This class builds the needed site from predefined html, js and css blocks
*/
class PageBuilder
{
	public $pageString;
	public $hasError;
	public $errorString;
	
	function __construct()
	{
		$this->hasError = false;
		$this->errorString = "";
	}

	public function frontpage()
	{
		$this->pageString = $this->loadFile("html/frontpage.html", false);
		$this->defaultSubstitution();
		require_once("DBLoader.php");
		$postArray = DBLoader::loadPage(10);
		$this->pageString = str_replace("[posts]", $this->postListSubstitution($postArray), $this->pageString);
	}
	public function debugPost()
	{
		$this->pageString = $this->loadFile("html/debugPost.html", false);
		$this->defaultSubstitution();
		require_once("DBLoader.php");
		$postArray = DBLoader::loadDebugPost();
		$this->pageString = str_replace("[posts]", $this->postListSubstitution($postArray), $this->pageString);
	}
	public function singlePost($index)
	{
		$this->pageString = $this->loadFile("html/singlePost.html", false);
		$this->defaultSubstitution();
		require_once("DBLoader.php");
		$postArray = DBLoader::loadPosts(array($index));
		$this->pageString = str_replace("[posts]", $this->postListSubstitution($postArray), $this->pageString);
	}

	public function printOut()
	{
		if($this->hasError)
			echo str_replace("\n", "<br>", $this->errorString);
		else
			echo $this->pageString;
	}
	private function defaultSubstitution()
	{
		$this->pageString = str_replace("[header]", $this->loadFile("header.html"), $this->pageString);
		$this->pageString = str_replace("[footer]", $this->loadFile("footer.html"), $this->pageString);
		$this->pageString = str_replace("[main]", $this->loadFile("main.html"), $this->pageString);
		$this->pageString = str_replace("[head]", $this->loadFile("head.html"), $this->pageString);
	}
	private function postListSubstitution($array)
	{
		$postString = "";
		for($i = 0; $i < sizeof($array); $i++)
		{
			if($i != 0)
				$postString .= $this->loadFile("separator.html");
			$postString .= $this->postSubstitution($this->loadFile("post.html"), $array[$i]);
		}
		return $postString;
	}
	private function postSubstitution($postString, $values)
	{
		$postString = str_replace("[html]", $values["html"], $postString);
		$replace = "";
		if ($values["js"] != "")
			$replace = '<script type="text/javascript">'.$values["js"].'</script>';
		$postString = str_replace("[js]", $replace, $postString);
		$replace = "";
		if ($values["css"] != "")
			$replace = '<style scoped type="text/css">'.$values["css"].'</style>';
		$postString = str_replace("[css]", $replace, $postString);
		$postString = str_replace("[index]", $values["index"], $postString);
		$postString = str_replace("[title]", $values["title"], $postString);
		$postString = str_replace("[date]", $values["date"], $postString);
		$postString = str_replace("[dir]", "entries/".$values["index"], $postString);
		return $postString;
	}
	private function loadFile($filename, $autoComplete = true)
	{
		if($autoComplete)
			$filename = "html/parts/" . $filename;
		if(file_exists($filename))
			return file_get_contents($filename);

		$this->hasError = true;
		$this->errorString .= "ERROR: Needed file ".$filename." not found!\n";
		return "";
	}
}


?>