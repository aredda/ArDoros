<?php

class GradeCategory
    extends Model
{
    /**
     * @type=VARCHAR(256)
     */
    public $icon;
    /**
     * @hasMany=Grade
     */
    public $grades;
}