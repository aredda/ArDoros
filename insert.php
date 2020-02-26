<?php

include "template.php";

$_GET["mode"] = "insert";
$_GET['model'] = ucfirst ($_GET['model']);

Template::useTemplate("اضافة", "views/form.php");