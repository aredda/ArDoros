<?php

include "template.php";
include "src/utilities/loader.php";
include "src/utilities/translator.php";

$_GET["mode"] = "update";

Template::useTemplate("اضافة", "views/form.php");