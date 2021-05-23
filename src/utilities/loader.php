<?php

abstract class Loader 
{
	public const HOST = "localhost";
	public const USER = "u621104737_areda";
	public const PASS = "txRwGcuS2#";
	public const DTBS = "u621104737_ardoros";

	public const APP_NAME = "";

	/**
	 * @var array
	 * Specifies all the directories that should be loaded before proceeding whatever operation
	 */
	public static $required = [
		"src/orm/collection",
		"src/orm",
		"src/models/abstract",
		"src/models"
	];

	/**
	 * @return string
	 * Returns the directory of the root
	 */
	public static function getAppDir ()
	{
		return "{$_SERVER['DOCUMENT_ROOT']}/" . self::APP_NAME;
	}

	/**
	 * Includes all setup directories that have been required in $required
	 */
	public static function load ()
	{
		foreach (self::$required as $directoryPath)
		{
			$fullDirectoryPath = self::getAppDir () . "/$directoryPath";

			$dir = opendir ($fullDirectoryPath);
	
			while (($item = readdir($dir)) !== false)
				if (strpos ($item, ".php"))
					include_once "$fullDirectoryPath/$item";
		}
	} 

	public static function getDatabase ()
	{
		if (!isset ($GLOBALS['db']))
			$GLOBALS["db"] = new DbArdoros (self::DTBS, new mysqli (self::HOST, self::USER, self::PASS));
	}
}

echo Loader::getAppDir();

// When 'loader.php' is included, load all required files, and get database
try
{
	Loader::load ();
	Loader::getDatabase ();
}
catch(Exception $e)
{
	echo $e->getMessage();
}
