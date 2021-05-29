<?php

include "templates/adminTemplate.php";

$_GET["mode"] = "insert";
$_GET['model'] = ucfirst ($_GET['model']);

AdminTemplate::useTemplate("إضافة", "views/form.php");