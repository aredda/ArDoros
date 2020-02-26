<?php

abstract class Uploader 
{
    /**
     * @var array
     * Permitted mime types for each property
     */
    public static $mimeTypes = [
        'image' => [
            'image/jpeg',
            'image/jpg'
        ],
        'path' => [
            'application/pdf'
        ]
    ];

    /**
     * @param array $files $_FILES array
     * @param array $meta Meta data of those files
     * @return array Where the key is the name, and the value is path
     */
    public static function upload (array $files, array $meta)
    {
        $uploaded = [];

        foreach ($files as $name => $details) 
        {
            // Check if the uploaded file matches the permitted properties
            if (!array_key_exists ($name, Uploader::$mimeTypes))
                throw new Exception ("Can't upload the file, unknown target.");
            
            // Check if there's actually a file
            if ($details['size'] == 0)
                continue;

            // Check if the type is allowed
            if (!in_array($details['type'], Uploader::$mimeTypes[$name]))
                throw new Exception ("Wrong file type given to $name!");

            // Construct and set up the new destination
            $modelDirectory = "media/" . strtolower ($meta['model']);

            // If the directory is not created
            if (!file_exists(Loader::getAppDir() . "/$modelDirectory"))
                mkdir (Loader::getAppDir() . "/$modelDirectory");

            // Construct the whole file path
            $destination = "$modelDirectory/" . time () . "." . pathinfo($details['name'], PATHINFO_EXTENSION);

            // Upload it to that destination
            move_uploaded_file($details['tmp_name'], Loader::getAppDir () . "/" . $destination);

            // Adding it to the array
            $uploaded[$name] = $destination;
        }

        return $uploaded;
    }
    
}