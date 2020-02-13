<?php

class Exercise
    extends PathModel
{
    /**
     * @type=INT
     * @references=Lesson
     */
    public $lesson;
    /**
     * @hasMany=ExerciseCorrection
     */
    public $corrections;
}