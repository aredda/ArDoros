<?php

abstract class Translator
{
    private static $synonyms = [

    ];

    public static function translate ($word)
    {
        return $word;
        // return self::$synonyms[$word];
    }
}