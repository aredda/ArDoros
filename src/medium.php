<?php

include "utilities/loader.php";
include "utilities/uploader.php";
include "utilities/handler.php";

// Handle incoming POST requests
try
{
    echo json_encode (['success' => RequestHandler::handle ($_POST, $_FILES)]);
}
catch (Exception $e)
{
    echo json_encode (['error' => $e->getMessage ()]);
}