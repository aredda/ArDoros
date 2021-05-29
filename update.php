<?php

include "templates/adminTemplate.php";

$_GET["mode"] = "update";
$_GET['model'] = ucfirst ($_GET['model']);

AdminTemplate::useTemplate("تعديل", "views/form.php");