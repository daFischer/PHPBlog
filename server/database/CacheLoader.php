<?php
require_once("LoaderInterface.php");
require_once("DBLoader.php");
require_once("/home/a1304351/public_html/blog/Cache.php");
/**
*  
*/
class CacheLoader implements LoaderInterface
{
	private static $loader;
	private $subLoader;

	function __construct($subLoader)
	{
		$this->pdo = pdoConnect();
		$this->subLoader = $subLoader;
	}

	public static function getInstance()
	{
		if (!self::$loader)
			self::$loader = new CacheLoader(new DBLoader());
		return self::$loader;
	}

	public function loadPage($amount, $page)
	{
		$cachename = "loadPage_".$amount."_".$page;
		$r = Cache::load($cachename);
		if (!$r) {
			$r = $this->subLoader->loadPage($amount, $page);
			Cache::save($cachename, $r);
		}
		return $r;
	}
	public function loadDebugPost()
	{
		$cachename = "loadDebugPost";
		$r = Cache::load($cachename);
		if (!$r) {
			$r = $this->subLoader->loadDebugPost();
			Cache::save($cachename, $r);
		}
		return $r;
	}

	public function loadPosts($indices)
	{
		$remaining = array();
		$result = array();

		// load all cached posts iteratively
		foreach ($indices as $index) {
			$cachename = "loadPost" . $index;
			$r = Cache::load($cachename);
			$result[] = $r;

			// If posts aren't cached, we'll have to load them afterwards
			if (!$r)
				$remaining[] = $index;
		}

		// Load missing posts if any
		if (sizeof($remaining > 0)) {
			$subResult = $this->subLoader->loadPosts($remaining);
			for ($i=0; $i < sizeof($result); $i++)
				if (!$result[i]) {
					$result[i] = $subResult.array_shift();
					Cache::save("loadPost" . $i, $result[i]);
				}
		}

		return $result;
	}

	public function insertPost($name)
	{
		$r = $this->subLoader->insertPost($name);
		Cache::clear();
		return $r;
	}
}


?>