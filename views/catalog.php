<?php

$filterPanel = array_reverse ([
    "grade" => null,
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

        echo "<div class='col-md-3 p-1'>";
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
        echo "<select class='w-100 p-2 filter-switch' name=$label>";
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
            <?php 
            echo "<script>";
            echo "$(document).ready (() => {";
            if($units->count() != 0)
                echo "$(\".banner-not-found\").hide();";
            echo "var list_view = $('.list-view');";
            foreach ($units as $u)
                echo "list_view.append (createListViewItem ('$u->id', '$u->title', 'lesson', '$u->image', '{$u->grade->title}', '{$u->subject->title}', '$unitModel', 'الدورة {$u->semester}'));";
            echo "})";
            echo "</script>";
            ?>
        </div>
    </div>
</div>