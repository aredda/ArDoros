<?php

abstract class Translator
{
    private static $synonyms = [
        "id" => "الرقم",
        "grade" => "المستوى",
        "subject" => "المادة",
        "lesson" => "الدرس",
        "title" => "العنوان",
        "path" => "مسار الملف",
        "semester" => "الدورة",
        "image" => "الصورة",
        "year" => "السنة",
        "insert" => "اضافة",
        "delete" => "حذف",
        "update" => "تعديل"
    ];

    public static function translate ($word)
    {
        if (array_key_exists($word, self::$synonyms))
            return self::$synonyms[$word];

        return $word;
    }
}