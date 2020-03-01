<?php

$data = $GLOBALS['db'];

foreach ($data->tables as $table)
{
?>
    <div class="nav-item">
        <div class="nav-item-header bg-grd-second">
            <?php echo "<a class='text-white' href='manage.php?model={$table->class}'>" . Translator::translate($table->class, true) . "</a>"; ?>
            <img class='ml-1' src="media/list_icon.png" />
        </div>
    </div>
<?php
}
