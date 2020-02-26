<?php

include "src/utilities/loader.php";
include "src/utilities/handler.php";

// NOTE TO MYSELF: 
// INSERT, DELETE & UPDATE are working

$_GET["type"] = Request::INSERT;
$_GET["model"] = "grade";
$_GET["id"] = 5;
$_GET["title"] = "الأولى ثانوي";

RequestHandler::handle ($_GET); 