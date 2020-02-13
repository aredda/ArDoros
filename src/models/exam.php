<?php

class Exam
    extends PathModel
{
    /**
     * @type=INT
     */
    public $year;
    /**
     * @hasMany=ExamCorrection
     */
    public $corrections;
    /**
     * @hasMany=ExamLesson
     */
    public $relatedLessons;
}