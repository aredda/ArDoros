<?php

abstract class Translator
{
    private static $synonyms = [
        "id" => "الرقم",
        "grade" => "المستوى",
        "subject" => "المادة",
        "lesson" => "الدرس",
        "exercise" => "التمرين",
        "exam" => "الامتحان",
        "title" => "العنوان",
        "path" => "مسار الملف",
        "semester" => "الدورة",
        "image" => "الصورة",
        "year" => "السنة",
        "type" => "النوع",
        "model" => "النوع",
        "insert" => "اضافة",
        "delete" => "حذف",
        "update" => "تعديل",
        "download" => "تحميل"
    ];

    public static function translate ($word)
    {
        $word = strtolower ($word);

        if (array_key_exists($word, self::$synonyms))
            return self::$synonyms[$word];

        return $word;
    }

    public static function convertSQLType ($sqlType)
    {
        if (strpos($sqlType, "INT") !== false)
            return "number";

        if (strpos($sqlType, "TEXT") !== false)
            return "textarea";

        return "text";
    }
}