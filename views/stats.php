<div class="row">
    <div class="col-md-12 mb-1">
        <h1 class='text-center m-0 mb-3 text-primary'>قاعدة البيانات<i class="fas fa-server ml-3"></i></h1>
    </div>
<?php
foreach ($GLOBALS['db']->tables as $table)
{
    $model = $table->class;
?>
    <div class="col-md-4 p-2">
        <a href='manage.php?model=<?php echo $model; ?>'>
            <div class="hover-shadow content-holder p-4">
                <h4 class='m-0 mb-2 text-dark'><?php echo Translator::translate($model, true); ?></h4>
                <div class='m-0 font-weight-bold'>
                    <span class='text-small text-muted'>عدد الوحدات</span>
                    <span class='float-left text-second h4 m-0'><?php echo $table->count (); ?></span>
                </div>
            </div>
        </a>
    </div>
<?php } ?>
</div>