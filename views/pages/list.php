<?php
    $model = ucfirst($_GET ["model"]);
    $reflector = new ReflectionClass ($model);
    $containers = SQLConverter::get_children_containers ($model);

    $downloadable = ["image", "path"];
?>

<div class="row px-3">
    <!-- Main Information -->
    <div class="col-md-12 content-holder p-4">
        <h2 class='m-0 mb-4 text-right text-primary'>
            <a href="insert.php?model=<?php echo $model; ?>" class='btn btn-lg bg-grd-second text-white float-left px-4 shadow-sm'>اضافة <?php echo Translator::translate ($model);?></a>
            <a><?php echo "جدول <b>ال" . Translator::translate($model, true) . "</b>"; ?></a>
        </h2>
        <table class='table table-striped border m-0 w-100'>
            <thead class='bg-light'>
                <?php 

                echo "<th></th>";
                echo "<th></th>";

                foreach ($reflector->getProperties () as $prop)
                    if (!in_array ($prop, $containers))
                        echo "<th>" . Translator::translate ($prop->getName()) . "</th>";

                ?>
            </thead>
            <tbody>
            <?php

                foreach ($GLOBALS["db"][$model] as $record)
                {
                    echo "<tr data-id='$record->id'>";
                    echo "<td><input class='btn-delete btn btn-sm btn-block btn-danger' type='button' value='". Translator::translate("delete") ."'/></td>";
                    echo "<td><a href='update.php?model=$model&id=$record->id'><input type='button' class='btn btn-sm btn-block btn-dark' value='" . Translator::translate("update") . "' /></a></td>";
                    foreach ($reflector->getProperties () as $prop)
                        if (!in_array ($prop, $containers))
                        {
                            $value = $prop->getValue ($record);

                            if (is_object($value))
                                $value = $value->title;

                            echo "<td>". (in_array($prop->getName(), $downloadable) ? "<a class='text-primary' download href='$value'>" . Translator::translate("download") . "</a>" : $value) . "</td>";
                        }
                    echo "</tr>";
                }

            ?>
            </tbody>
        </table>
    </div>
</div>
