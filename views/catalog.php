<?php

$filterPanel = array_reverse ([
    "grade" => ['-'],
    "subject" => null,
    "semester" => [1, 2],
    "model" => [Lesson::class, Exercise::class, Exam::class]
]);

$criteria = $_GET;
$criteria['type'] = Request::SEARCH;
$criteria['model'] = Lesson::class;

// Returns a Table instance
$units = RequestHandler::handle ($criteria, null);

$unitModel = Translator::translate ($criteria['model']);

?>
<!-- Filtering Panel -->
<div class="row px-3">
    <?php
    foreach ($filterPanel as $label => $values)
    {
        $items = is_null($values) ? $GLOBALS['db'][ucfirst($label)] : $values;
        echo "<div class='col-md p-1'>";
            echo "<div class='d-flex flex-row'>";
                echo "<div class='flex-fill text-left'>";
                    echo "<label class='switch'>";
                        echo "<input type='checkbox' checked>";
                        echo "<span class='slider round'></span>";
                    echo "</label>";
                echo "</div>";
                echo "<div class='label flex-fill'>";
                    echo "<h6 class='text-first font-weight-bold'>" . Translator::translate($label) . "</h6>";
                echo "</div>";
            echo "</div>";
            echo "<select id='cb-$label' class='w-100 p-2 filter-switch' name=$label>";
                if(count($items) == 0)
                    echo "<option>-</option>";
                foreach ($items as $item)
                {
                    if (is_a ($item, ucfirst($label)))
                        echo "<option value=$item->id>$item->title</option>";
                    else
                        echo "<option value=$item>" . Translator::translate($item) . "</option>";
                }
            echo "</select>";
        echo "</div>";
    }
    // GradeCategory ComboBox
    echo "<div class='col-md p-1'>";
        echo "<h6 class='text-first font-weight-bold'>" . Translator::translate('gradecategory') . "</h6>";
        echo "<select id='cb-grade-category' class='w-100 p-2 filter-switch'>";
        foreach ($GLOBALS['db'][GradeCategory::class] as $gc)
            echo "<option value=$gc->id>$gc->title</option>";
        echo "</select>";
    echo "</div>";
    ?>
</div>

<!-- Result List Item -->
<div class="row px-3 py-4">
    <div class="col-md-12">
        <div class="row p-3 list-view content-holder">
            <div class="col-md-12 py-2">
                <h3 class="m-0 p-0 text-second"><span class='text-secondary float-left result-counter'><?php echo $units->count (); ?></span>:عدد النتائج</h3>
                <div class='banner-not-found mt-4'>
                    <h1 class="text-center display-1 text-secondary m-0 mt-3"><i class="far fa-frown"></i></h1>
                    <p class="text-center text-secondary m-0 h4 mt-2">..عذرا، يتعذر إيجاد أي نتائج</p>
                </div>
            </div>
            <div class="col-md-12 py-2">
                <nav>
                    <ul class="pagination text-secondary justify-content-center">
                        <li class="page-item page-card-view active">
                            <a class="page-link">
                                <i class="fas fa-th"></i>
                            </a>
                        </li>
                        <li class="page-item page-list-view mr-3">
                            <a class="page-link">
                                <i class="fas fa-th-list"></i>
                            </a>
                        </li>
                        <li class="page-item text-first page-prev">
                            <a class="page-link">السابق</a>
                        </li>
                        <li class="page-item text-first page-next">
                            <a class="page-link">التالي</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <script src="js/on_load.js"></script>
        </div>
    </div>
</div>