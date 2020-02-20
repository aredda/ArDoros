<?php

class Grade
    extends Model
{
    /**
     * @hasMany=Lesson
     */
    public $lessons;
}