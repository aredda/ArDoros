<?php

class ExamLesson
{
    /**
     * @type=INT
     * @auto
     * @primary
     */
    public $id;
    /**
     * @type=INT
     * @references=Exam
     */
    public $exam;
    /**
     * @type=INT
     * @references=Lesson
     */
    public $lesson;
}