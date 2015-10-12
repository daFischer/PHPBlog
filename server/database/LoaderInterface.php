<?php

/**
*  
*/
interface LoaderInterface
{
	public function loadPage($amount, $page);
	//public function loadDebugPost();
	public function loadPosts($indices);
}


?>