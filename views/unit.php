<?php 

// If the required information isn't provided, redirect to the home page
if (!isset ($_GET['model']) || !isset ($_GET['id']))
        header ('Location: index.php');

$model = ucfirst ($_GET['model']);

$container = $GLOBALS['db'][$model];
$instance = $container->find ($_GET['id']);

if (!file_exists (Loader::getAppDir () . '/' . $instance->path))
{
    // Show notification
    echo "<script> popNotification ('عذرا', 'يتبين أن الملف الذي تريده غير متوفر', 'error'); </script>";
    // Redirect to main page
    header('refresh: 1; url= index.php');
}
else
{
$size = filesize (Loader::getAppDir () . "/{$instance->path}");
$tags = [];
$attachments = [];

switch ($model)
{
    case Lesson::class:
        // Set up tags
        array_push ($tags, $instance->grade->title, $instance->subject->title, "الدورة " . $instance->semester, Translator::translate ($model));
        // Set up attachments
        if ($instance->exercises->count () > 0)
            $attachments['تمارين متعقلة بهذ الدرس'] = $instance->exercises;
        if ($instance->relatedExams->count () > 0)
            $attachments['امتحانات متعقلة بهذ الدرس'] = $instance->relatedExams;
    break;
    
    case Exercise::class:
        $lesson = $instance->lesson;
        // Set up tags
        array_push ($tags, $lesson->grade->title, $lesson->subject->title, "الدورة " . $lesson->semester, Translator::translate ($model));
        // Set up attachments
        if ($lesson->exercises->count () > 0)
            $attachments['تمارين مشابهة'] = $lesson->exercises;
        if ($instance->corrections->count () > 0)
            $attachments['تصحيحات لهذا التمرين'] = $instance->corrections;
    break;

    case Exam::class:
        // Set up tags
        array_push ($tags, $instance->year);
        // Set up related lessons
        if ($instance->relatedLessons->count () > 0)
            $attachments['دروس متعلقة بهذا الامتحان'] = $instance->relatedLessons;
        // Set up related corrections
        if ($instance->corrections->count () > 0)
            $attachments['تصحيحات لهذا الامتحان'] = $instance->corrections;
    break;
}
?>
<div class="row px-3 pb-4">
    <!-- Main Information -->
    <div class="col-md-12 content-holder p-4">
        <h1 class="text-second"><?php echo $instance->title; ?></h1>
        <h5 class="m-0 py-3">
            <?php 
            foreach ($tags as $tag)
            echo "<span class='badge text-white p-2 bg-grd-first'>$tag</span> "; ?>
            <span class="float-left text-first-dark"><?php echo "ميغابايت " . round (($size / 1024 / 1024), 2); ?></span>
        </h5>
        <embed class="document w-100 mt-1 rounded border border-dark" src="<?php echo $instance->path; ?>" />
        <a href="<?php echo $instance->path; ?>">
            <button class="button btn-block bg-grd-first text-center py-3 mt-2 shadow rounded inverse-first">تحميل</button>
        </a>
    </div>
    <!-- Related Attachments -->
    <?php foreach ($attachments as $label => $items) { ?>
        <div class="col-md-12 content-holder p-4 mt-3">
            <h3 class="text-second text-center m-0"><?php echo $label; ?></h3>
            <ul class="list-group list-group-flush mt-2">
            <?php
                foreach ($items as $item)
                {
                    /** Adjusting the item */
                    $i = is_a ($item, ExamLesson::class) ? (strcmp ($container->class, Lesson::class) == 0 ? $item->exam : $item->lesson) : $item;
                    // Get the model
                    $itemModel = get_class($i);
                    // Check if the path of the file exists
                    if (!file_exists (Loader::getAppDir() . "/$i->path"))
                        continue;
                    ?>
                <li class="list-group-item">
                    <a class="text-first" href="unit.php?<?php echo "model=$itemModel&id={$i->id}";?>"><?php echo $i->title;?></a>
                    <span class="float-left text-small text-muted"><?php echo "ميغابايت " . round (filesize (Loader::getAppDir () . "/{$i->path}") / 1024 / 1024, 2);?></span>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
</div>

<?php }