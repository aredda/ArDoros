<?php

class Grade
    extends Model
{
    /**
     * @hasMany=Subject
     */
    public $subjects;
}