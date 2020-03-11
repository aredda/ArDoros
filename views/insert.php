<?php

include "templates/adminTemplate.php";

$_GET["mode"] = "insert";
$_GET['model'] = ucfirst ($_GET['model']);

AdminTemplate::useTemplate("اضافة", "views/form.php");