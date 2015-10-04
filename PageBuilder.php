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
		require_once("DBLoader.php");
		$postArray = DBLoader::loadPosts(10);
		$postString = "";
		for($i = 0; $i < sizeof($postArray); $i++)
		{
			if($i != 0)
				$postString .= $this->loadFile("separator.html");
			$postString .= $this->postSubstitution($this->loadFile("post.html"), $postArray[$i]);
		}
		$this->pageString = str_replace("[posts]", $postString, $this->pageString);
		$this->pageString = str_replace("[head]", "", $this->pageString);
	}
	private function postSubstitution($postString, $values)
	{
		$postString = str_replace("[index]", $values["index"], $postString);
		$postString = str_replace("[title]", $values["title"], $postString);
		$postString = str_replace("[date]", $values["date"], $postString);
		$postString = str_replace("[html]", $values["html"], $postString);
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