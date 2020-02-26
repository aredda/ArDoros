<?php

include "src/utilities/loader.php";

$arr = [];

$reflector = new ReflectionClass (Lesson::class);

$arr[] = $GLOBALS['db'][Lesson::class]->get (0);

echo json_encode ($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
echo json_last_error_msg();

/**
 * So in order to solve this puzzle, we need to understand the problem:
 * 
 * 1) first of all, json_encode won't encode an array that contains recursive connections
 * for example, let's imagine two entities
 * Grade (id, Lessons)
 * Lesson (title, path, #Grade)
 * The Grade model has a collection of lessons, meanwhile the Lesson model has a reference to its Grade,
 * this will cause the encoder to enter a recursive operatin as it tries to encode.
 * 
 * now, how can we solve this issue?
 * I have some propositions in mind
 * 1) set all containers in the parent instance to null:
 * - this will require looping through the properties then verifying if the property
 *      contains a @hasMany annotation, if so, we shall set the property's value to null
 */

echo "<hr/>";


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

echo json_encode (removeRecursion ($GLOBALS['db'][Lesson::class]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);