<?php

class Grade
    extends Model
{
    /**
     * @hasMany=Lesson
     */
    public $lessons;

    /**
     * @type=INT
     * @references=GradeCategory
     */
    public $category;
}