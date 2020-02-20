<?php

$ROOT_DIR = "{$_SERVER['DOCUMENT_ROOT']}/ArDorossV2";

/** Auto-loading function */
function load ($directoryPath)
{
	$dir = opendir ($directoryPath);

	while (($item = readdir($dir)) !== false)
		if ( strpos ($item, ".php") )
			include_once "$directoryPath/$item";
}

// Include necessary entities
load ("$ROOT_DIR/src/orm/collection");
load ("$ROOT_DIR/src/orm");
load ("$ROOT_DIR/src/models/abstract");
load ("$ROOT_DIR/src/models");

// Data Container
$host = "localhost";
$user = "areda";
$pass = "123";

$GLOBALS["db"] = new DbArdoros (new mysqli ($host, $user, $pass));