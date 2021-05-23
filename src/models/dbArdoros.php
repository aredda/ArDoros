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

        // If the database is not created, create it
        $this->create ();
    }
}