<?php

$data = $GLOBALS['db'];

// Reload database EAGERLY
$data->refresh ();

$grades = $data[Grade::class];
$lessons = $data[Lesson::class];

foreach ($grades as $grade)
{
    // Get the lessons of this grade
    $gradeLessons = $grade->lessons;
    // Get the subjects of this grade in which there are lessons
    $gradeSubjects = new Table (Subject::class);
    foreach ($gradeLessons as $lesson)
        if ($gradeSubjects->find ($lesson->subject->id) == null)   
            $gradeSubjects->add ($lesson->subject);        
?>
    <div class="nav-item">
        <div class="nav-item-header bg-grd-second">
            <a><?php echo $grade->title; ?></a>
            <span>&rtrif;</span>
        </div>
        <?php
        if ($gradeSubjects->count () > 0)
        {
            echo "<div class='nav-item-body bg-white'>";
            foreach ($gradeSubjects as $s)
                echo "<a href='index.php?grade=$grade->id&subject=$s->id' class='nav-item'>$s->title</a>";
            echo "</div>";
        }
        ?>
    </div>
<?php
}
