<?php

/**
* 
*/
class Cache
{
	/**
	 * Sanitizes variable names to reasonable filenames
	 * Just removes everything except 0-9a-zA-Z (for now)
	 */
	private static function nameToFile($value)
	{
		$result = "cache/";
		for ($i=0; $i < strlen($value); $i++) {
			$o = ord(substr($value, $i, 1));
			if(($o >= 48 && $o < 58) || ($o >= 65 && $o < 91)
									 || ($o >= 97 && $o < 122))
				$result .= substr($value, $i, 1);
		}
		return $result . ".txt";
	}

	/**
	 * Saves the given $value in a file to avoid database stress
	 * It can later be reloaded by loading with the same $name
	 */
	public static function save($name, $value)
	{
		// make sure cache/ exists
		// cache might be "cleared" by deleting the folder
		if (!file_exists('cache'))
			mkdir('cache');

		// the given name has to be escaped
		$filename = self::nameToFile($name);

		// create or overwrite the file
		file_put_contents($filename, serialize($value));
	}

	/**
	 * Loads the saved value from a file to avoid database stress
	 * Returns false if not found
	 */
	public static function load($name)
	{
		// the given name has to be escaped
		$filename = self::nameToFile($name);

		// Returning false if the file doesn't exist allows for easy handling
		// TODO: This might just be a problem when saving a single bool
		if (!file_exists($filename)) {
			return false;
		}

		// load value from the file
		return unserialize(file_get_contents($filename));
	}

	/**
	 * Clears the cache completely
	 */
	public static function clear()
	{
		if(is_dir("cache"))
			self::recursiveDelete("cache");
	}

	/**
	 * Recursively removes a directory
	 */
	private static function recursiveDelete($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			if(is_dir("$dir/$file"))
				self::recursiveDelete("$dir/$file");
			else
				unlink("$dir/$file");
		}
		return rmdir($dir);
	}
}

?>