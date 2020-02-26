<?php

include "template.php";

$_GET['model'] = ucfirst ($_GET['model']);

Template::useTemplate('لوحة التحكم', "views/list.php");