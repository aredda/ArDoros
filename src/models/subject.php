<?php

class Subject
    extends Model
{
    /**
     * @hasMany=Lesson
     */
    public $lessons;
}