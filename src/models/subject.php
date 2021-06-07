<?php

class Subject
    extends Model
{
    /**
     * @type=VARCHAR(256)
     */
    public $icon;
    /**
     * @type=TEXT
     */
    public $image;
    /**
     * @hasMany=Lesson
     */
    public $lessons;
}