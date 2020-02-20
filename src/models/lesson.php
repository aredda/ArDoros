<?php

class Lesson
    extends PathModel
{
    /**
     * @type=TEXT
     */
    public $image;   
    /**
     * @type=INT 
     */   
    public $semester;
    /**
     * @type=INT
     * @references=Grade
     */
    public $grade;
    /**
     * @type=INT
     * @references=Subject
     */
    public $subject;
    /**
     * @hasMany=Exercise
     */
    public $exercises;
    /**
     * @hasMany=ExamLesson
     */
    public $relatedExams;
}