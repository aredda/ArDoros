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
        echo "<h6 class='text-first font-weight-bold'>" . Translator::translate($label) . "</h6>";
        echo "<select class='w-100 p-2' name=$label>";
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
                <h3 class="m-0 p-0 text-second"><span class='text-primary float-left result-counter'><?php echo $units->count (); ?></span>:عدد النتائج</h3>
            </div>
            <?php 
            echo "<script>";
            echo "$(document).ready (() => {";
            echo "var list_view = $('.list-view');";
            foreach ($units as $u)
                echo "list_view.append (createListViewItem ('$u->id', '$u->title', 'lesson', '$u->image', '{$u->grade->title}', '{$u->subject->title}', '$unitModel', 'الدورة {$u->semester}'));";
            echo "})";
            echo "</script>";
            ?>
        </div>
    </div>
</div>