<?php

class DbArdoros
    extends Database
{
    public function setup ()
    {
        // Determining the classes to map
        $this->map (
            Grade::class,
            Subject::class,
            Lesson::class,
            Exercise::class,
            Exam::class,
            ExerciseCorrection::class,
            ExamCorrection::class,
            ExamLesson::class
        );

        // Select database
        $this->select_db ($this->name);
        // If it is indeed created, load data
        $this->refresh ();
    }
}