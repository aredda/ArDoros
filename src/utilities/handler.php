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
    public static function fillInstance ($instance, ReflectionClass $reflector, array $params)
    {
        foreach ($params as $key => $value)
            if ($reflector->hasProperty ($key))
                $reflector->getProperty ($key)->setValue ($instance, $value);

        return $instance;
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
            throw new Exception ("A Request Type is expected!");

        if (!isset($params["model"]))
            throw new Exception ("A Model is needed to be handled!");

        $request = $params["type"];

        // The targeted model
        $model = ucfirst ($params["model"]);
        // The reflection helper of the model
        $reflector = new ReflectionClass ($model);
        // The primary key for that model
        $pk = SQLConverter::get_primary_property ($model);
        // The table of the model
        $container = $GLOBALS["db"][$model];

        switch ($request)
        {
            case Request::INSERT:

                // Adding the instance to the container
                $container->add (RequestHandler::fillInstance (RequestHandler::fillInstance ($reflector->newInstance(), $reflector, Uploader::upload ($files, $params)), $reflector, $params));

            break;

            case Request::UPDATE;
            
                // Targeted instance
                $instance = $container->find ($params["id"]);
                // Remove the 'id' param from 'params' array
                unset ($params['id']);
                // Update the instance
                $container->update (RequestHandler::fillInstance (RequestHandler::fillInstance ($instance, $reflector, Uploader::upload ($files, $params)), $reflector, $params));

            break;
        
            case Request::DELETE:

                // Removing the record
                $container->remove ($params["id"]);

            break;

            case Request::SEARCH:
            /**
             * INPUT: 
             * - model's name
             * - criteria
             */
            break;
        }

        // Save changes to database
        $GLOBALS["db"]->refresh ();
    }   
}