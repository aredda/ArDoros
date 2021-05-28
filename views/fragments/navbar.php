<?php

$data = $GLOBALS['db'];

// Reload database EAGERLY
$data->refresh ();
$gradeCategories = $data[GradeCategory::class];

foreach ($gradeCategories as $category)
{
?>
    <div class="nav-item">
        <div class="nav-item-header bg-grd-second d-flex flex-row-reverse align-items-center">
            <i style='width: 20px' class="<?php echo $category->icon; ?> ml-2"></i>
            <div class="flex-fill">
                <a class='h5'><?php echo "تعليم " . $category->title; ?></a>
            </div>
            <i class="fas fa-chevron-right chevrolet"></i>
        </div>
    </div>
<?php
}
