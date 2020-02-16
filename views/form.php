<div class="row px-3">
    <!-- Main Information -->
    <div class="col-md-12 content-holder p-4">
        <table class="w-100">
<?php

function convertSQLType ($sqlType)
{
    if (strpos($sqlType, "INT") !== false)
        return "number";

    if (strpos($sqlType, "TEXT") !== false)
        return "textarea";

    return "text";
}

$model = $_GET ["model"];

$foreignKeys = SQLConverter::get_foreign_keys ($model);
$containers = SQLConverter::get_children_containers ($model);
$fileProps = ["image", "path"];

$reflector = new ReflectionClass ($model);

foreach ($reflector->getProperties () as $prop)
{
    $type = SQLConverter::get_constraint ($prop, "@type");
    $label = Translator::translate ($prop->getName ());
    
    $reference = null;
  
    if (in_array ($prop, $containers))
        continue;

    if (SQLConverter::get_constraint ($prop, "@auto"))
        continue;

    if (array_key_exists ($prop->getName(), $foreignKeys))
    {
        $type = "MULTI";
        $reference = $foreignKeys[$prop->getName ()];
    }

    if (in_array($prop->getName(), $fileProps))
        $type = "FILE";

    echo "<tr>";
    echo "<td>";
    switch ($type)
    {
        case "MULTI":
            echo "<select name='{$prop->getName()}' class='form-control mb-2'>";
            /** Fill the select control with records of the referenced model */
            echo "</select>";
        break;

        case "FILE":
            echo "<input type='file' class='mb-2 float-left' name='{$prop->getName()}'/>";
        break;

        default:
            echo "<input type='" . convertSQLType($type) . "' name='{$prop->getName()}' class='form-control mb-2' />";
        break;
    }
    echo "</td>";
    echo "<td class='text-first font-weight-bold'>$label</td>";
    echo "</tr>";
}

?>
            <tr>
                <td colspan="2">
                    <input type="submit" value="insert" class="button btn-block rounded shadow bg-grd-second text-center mt-2 p-2" />
                </td>
            </tr>
        </table>
    </div>
</div>