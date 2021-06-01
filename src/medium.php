<?php

include "utilities/loader.php";
include "utilities/uploader.php";
include "utilities/handler.php";
include "utilities/translator.php";
include "utilities/paginator.php";

/**
 * Remove any signs of recursive connections
 */
function removeRecursion (Table $a)
{
    $aReflector = new ReflectionClass ($a->class);

    foreach (['@references', '@hasMany'] as $annotation)
    foreach ($aReflector->getProperties () as $aProperty)
    {
        $aTarget = SQLConverter::get_constraint($aProperty, $annotation); 
        
        if ($aTarget == null) continue;

        switch ($annotation)
        {
            case '@references':
                foreach ($a as $aRecord)
                {
                    $bRecord = $aProperty->getValue ($aRecord);
                    $bContainers = SQLConverter::get_children_containers ($aTarget);

                    foreach ($bContainers as $bContainer)
                    {
                        if (strcmp (SQLConverter::get_constraint($bContainer, '@hasMany'), get_class($a)) != 0)
                            continue;

                        $bContainer->setValue ($bRecord, null);
                    }
                }
            break;

            case '@hasMany':
                $bTarget = SQLConverter::search_property ($aTarget, '@references', $a->class);
                
                foreach ($a as $aRecord)
                {
                    $aContainer = $aProperty->getValue ($aRecord);
                    
                    if ($aContainer != null)
                    foreach ($aContainer as $bRecord)
                        $bTarget->setValue ($bRecord, null);
                }
            break;
        }
    }

    return $a;
}

/**
 * Tweak some values before sending the data
 */
function purify (Table $table)
{
    $table->class = Translator::translate ($table->class);

    return $table;
}

// Handle incoming POST requests
if (count ($_POST) > 0)
{
    try
    {
        $response = RequestHandler::handle ($_POST, $_FILES);
    
        // If the returned response is a Table instance
        if (is_a($response, Table::class))
        {
            // Then purify it from recursion
            $response = purify ($response);
            // And apply pagination if requested
            if(isset($_POST['paginate']))
            {
                $page = $_POST['page'] ?? 1;
                $per_page = $_POST['per_page'] ?? Paginator::MAX_PER_PAGE;
                $response = new Paginator($response, $page, $per_page);
            }
        }

        echo json_encode (['success' => $response], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    catch (Exception $e)
    {
        echo json_encode (['error' => $e->getMessage ()]);
    }
}