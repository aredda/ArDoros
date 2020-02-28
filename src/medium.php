<?php

include "utilities/loader.php";
include "utilities/uploader.php";
include "utilities/handler.php";
include "utilities/translator.php";

/**
 * Remove any signs of recursive connections
 */
function removeRecursion (Table $table)
{
    $reflector = new ReflectionClass ($table->class);

    foreach ($reflector->getProperties () as $prop)
    {
        $reference = SQLConverter::get_constraint ($prop, "@references");

        if ($reference != null)
            foreach (SQLConverter::get_children_containers ($reference) as $cp)
                foreach ($table as $record)
                    $cp->setValue ($prop->getValue ($record), null);
    }

    return $table;
}

/**
 * Tweak some values before sending the data
 */
function tweak (Table $table)
{
    $table->class = Translator::translate ($table->class);

    return $table;
}

// Handle incoming POST requests
try
{
    $response = RequestHandler::handle ($_POST, $_FILES);

    if (is_a($response, Table::class))
        $response = tweak (removeRecursion ($response));

    echo json_encode (['success' => $response], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
catch (Exception $e)
{
    echo json_encode (['error' => $e->getMessage ()]);
}