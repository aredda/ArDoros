<?php

class Subject
    extends Model
{
    /**
     * @type=VARCHAR(256)
     */
    public $icon;
    /**
     * @hasMany=Lesson
     */
    public $lessons;
}