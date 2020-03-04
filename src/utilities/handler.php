<?php

abstract class Request
{
    public const INSERT = "insert";
    public const UPDATE = "update";
    public const DELETE = "delete";
    public const SEARCH = "search";
}

abstract class RequestHandler
{
    /**
     * Responsible for filling an instance of a model using 
     * request's parameters
     */
    private static function fill ($instance, ReflectionClass $reflector, array $params)
    {
        foreach ($params as $key => $value)
            if ($reflector->hasProperty ($key))
                $reflector->getProperty ($key)->setValue ($instance, $value);

        return $instance;
    }

    /**
     * Responsible for deleting related files of a record
     */
    private static function deleteFiles ($record)
    {
        // Remove related documents
        $files = [$record->path];
        // If the record is a Lesson, add the image too
        if (is_a ($record, Lesson::class))
            $files[] = $record->image;
        // Remove files
        Uploader::delete ($files);
    }

    /**
     * @param array $params
     * $_POST/$_GET array
     * @param array $files
     * $_FILES array
     */
    public static function handle (array $params, array $files = null)
    {
        if (!isset($params["type"]))
            throw new Exception ("A request Type is expected!");

        if (!isset($params["model"]))
            throw new Exception ("A model is needed to be handled!");

        $request = $params["type"];

        // The targeted model
        $model = ucfirst ($params["model"]);
        // The reflection helper of the model
        $reflector = new ReflectionClass ($model);
        // Change refresh mode to LAZY MODE to avoid recursion
        $GLOBALS['db']->refresh (true);
        // The table of the model
        $container = $GLOBALS['db'][$model];

        switch ($request)
        {
            case Request::INSERT:

                // Check if all inputs are filled
                foreach ($params as $key => $value)
                    if (empty ($value))
                        throw new Exception ('المرجو ادخال جميع المعلومات');
                // Adding the instance to the container
                $container->add (RequestHandler::fill (RequestHandler::fill ($reflector->newInstance(), $reflector, Uploader::upload ($files, $params)), $reflector, $params));

            break;

            case Request::UPDATE;
            
                // Targeted instance
                $instance = $container->find ($params["id"]);
                // Uploaded files
                $uploaded = Uploader::upload ($files, $params);
                // Remove old related documents, if there are new uploaded documents
                if (count ($uploaded) > 1)
                    self::deleteFiles ($instance);
                // Remove the 'id' param from 'params' array
                unset ($params['id']);
                // Update the instance
                $container->update (RequestHandler::fill (RequestHandler::fill ($instance, $reflector, $uploaded), $reflector, $params));

            break;
        
            case Request::DELETE:

                // Retrieve the whole record
                $record = $container->find ($params['id']);
                // Removing the record
                $container->remove ($record->id);
                // Remove related documents
                if ($reflector->hasProperty ('path'))
                    self::deleteFiles ($record);

            break;

            case Request::SEARCH:

                // Determining the filtering method using the model
                // This is the filter method of the Lesson class
                $filterMethod = function ($iterator, array $criteria) 
                {
                    $result = true;
                    $reflector = new ReflectionClass (get_class ($iterator));
                    
                    foreach ($criteria as $key => $value)
                    {
                        if (is_string ($value) && empty ($value))
                                continue;
                        
                        if ($reflector->hasProperty ($key))
                        {
                            $p = $reflector->getProperty($key);
                            $v = $p->getValue ($iterator);

                            if (strpos($key, 'title') !== false)
                                $result &= (strpos ($v, $value) !== false);
                            else
                                $result &= !is_object ($v) ? $v == $value : $v->id == $value;
                        }
                    }

                    return $result;
                };
                // Choose filter model
                switch ($model)
                {
                    case Exercise::class:

                        $filterMethod = function ($iterator, array $criteria)
                        {
                            $lesson = $iterator->lesson;
                            // Search for the exercises of that lesson whose title is similar to the one provided in criteria
                            $titleCheck = empty ($criteria['title']) ? true : strpos ($lesson->title, $criteria['title']) !== false;

                            return $titleCheck  && ($lesson->grade->id == $criteria['grade'] 
                                                && $lesson->subject->id == $criteria['subject']
                                                && $lesson->semester == $criteria['semester']);
                        };

                    break;

                    case Exam::class:

                        $filterMethod = function ($iterator, array $criteria)
                        {
                            if (empty ($criteria['title']))
                                return true;
                            
                            return $iterator->relatedLessons->where (function ($i, array $c) {
                                return strpos ($i->lesson->title, $c['title']) !== false;
                            }, $criteria)->count () > 0 ;
                        };

                    break;
                }
                // Return the result of the filtering operation
                return (count ($params) == 0 ? $container : $container->where ($filterMethod, $params));

            break;
        }

        // Save changes to database
        $GLOBALS["db"]->refresh ();
    }   
}