<?php

include "templates/adminTemplate.php";

$_GET['model'] = ucfirst ($_GET['model']);

AdminTemplate::useTemplate('لوحة التحكم', "views/list.php");