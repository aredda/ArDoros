<div class="row px-3">
    <!-- Main Information -->
    <div class="col-md-12 content-holder p-4">
        <h2 class='m-0 mb-4 text-right text-primary'>
            <a class='btn btn-lg bg-grd-second text-white float-left px-4 shadow-sm'>اضافة</a>جدول المعلومات
        </h2>
        <table class='table table-striped border m-0 w-100'>
            <thead class='bg-grd-second text-white'>
                <?php 
                
                $model = ucfirst($_GET ["model"]);
                $reflector = new ReflectionClass ($model);

                $containers = SQLConverter::get_children_containers ($model);
                
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
                    echo "<tr>";
                    echo "<td><input class='btn btn-sm btn-block btn-danger' type='submit' value='". Translator::translate("delete") ."'/></td>";
                    echo "<td><input class='btn btn-sm btn-block btn-primary' type='submit' value='". Translator::translate("update") ."'/></td>";
                    foreach ($reflector->getProperties () as $prop)
                        if (!in_array ($prop, $containers))
                        {
                            $value = $prop->getValue ($record);

                            if (is_object($value))
                                $value = $value->title;

                            echo "<td>$value</td>";
                        }
                    echo "</tr>";
                }

            ?>
            </tbody>
        </table>
    </div>
</div>
