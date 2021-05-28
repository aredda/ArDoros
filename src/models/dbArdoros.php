<?php

class DbArdoros
    extends Database
{
    public function setup ()
    {
        // Determining the classes to map
        $this->map (
            GradeCategory::class,
            Grade::class,
            Subject::class,
            Lesson::class,
            Exercise::class,
            Exam::class,
            ExerciseCorrection::class,
            ExamCorrection::class,
            ExamLesson::class
        );

        /** 
         * IMPORTANT: First, run this one, then comment it
         * */
        $this->create();

        // Select database
        // $this->connection->select_db ($this->name);
        // If it is indeed created, load data
        // $this->refresh ();
    }
}
