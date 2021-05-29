<?php

$model = $_GET ["model"];
$mode = $_GET["mode"];

// Concerning UPDATE mode
$container = $GLOBALS['db'][$model] ?? null;
$id = $_GET['id'] ?? null;
$target = $container->find ($id) ?? null;

$foreignKeys = SQLConverter::get_foreign_keys ($model);
$containers = SQLConverter::get_children_containers ($model);
$fileProps = ["image", "path"];

$reflector = new ReflectionClass ($model);

$submitId = $mode == "insert" ? "btn-insert" : "btn-update";

?>

<div class="row px-3">
    <!-- Main Information -->
    <div class="col-md-12 content-holder p-4">
        <div class="mb-4 d-flex align-content-center">
            <a href="manage.php?model=<?php echo $model; ?>" class="h2 text-secondary">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h2 class='m-0 flex-fill text-second'><?php echo Translator::translate($mode) . " <b>" . Translator::translate($model) . "</b>"; ?></h2>
        </div>
        <form id="form" method='post' enctype="multipart/form-data">
        <table class="w-100">
<?php
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
            echo "<select required name={$prop->getName()} class='form-control mb-2 text-center'>";
            foreach ($GLOBALS['db'][$reference] as $record)
                echo "<option " . ($record->id == $prop->getValue ($target)->id ? "selected" : "") . " value='$record->id'>$record->title</option>";
            echo "</select>";
        break;

        case "FILE":
            ?>
            <div class="input-group mb-2">
                <div class="custom-file">
                    <input required type="file" name='<?php echo $prop->getName(); ?>' class="custom-file-input">
                    <label class="custom-file-label text-left">اختر الملف</label>
                </div>
            </div>
            <?php
        break;

        default:
            $value = $target == null ? "" : $prop->getValue ($target);

            echo "<input required type=".Translator::convertSQLType ($type)." name='{$prop->getName()}' value='$value' class='form-control text-left mb-2' />";
        break;
    }
    echo "</td>";
    echo "<td class='text-dark font-weight-bold'>ال$label</td>";
    echo "</tr>";
}
?>
            <tr>
                <td colspan="2">
                    <input id="<?php echo $submitId; ?>" type="button" value="<?php echo Translator::translate($_GET['mode']); ?>" class="button btn-block rounded shadow bg-grd-first inverse-first text-center mt-2 p-2" />
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>