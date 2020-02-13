<?php

class Subject
    extends Model
{
    /**
     * @type=INT
     * @references=Grade
     */
    public $grade;
    /**
     * @hasMany=Lesson
     */
    public $lessons;
}