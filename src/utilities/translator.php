<?php

abstract class Translator
{
    private static $synonyms = [
        "id" => "رقم",
        "grade" => "مستوى",
        "subject" => "مادة",
        "lesson" => "درس",
        "exercise" => "تمرين",
        "exam" => "امتحان",
        "title" => "عنوان",
        "path" => "ملف",
        "semester" => "دورة",
        "image" => "صورة",
        "year" => "سنة",
        "type" => "نوع",
        "model" => "نوع",
        "insert" => "اضافة",
        "delete" => "حذف",
        "update" => "تعديل",
        "download" => "تحميل",
        'icon' => "أيقونة",
        'exercisecorrection' => 'تصحيح تمرين',
        'examcorrection' => 'تصحيح امتحان',
        'gradecategory' => 'مستوى تعليم',
        'category' => 'مستوى التعليم'
    ];

    private static $plurals = [
        'grade' => 'مستويات',
        'subject' => 'مواد',
        'lesson' => 'دروس',
        'exercise' => 'تمارين',
        'exam' => 'امتحانات',
        'exercisecorrection' => 'تصحيحات التمارين',
        'examcorrection' => 'تصحيحات الامتحانات',
        'gradecategory' => 'مستويات التعليم'
    ];

    public static function translate ($word, $toPlural = false)
    {
        $word = strtolower ($word);

        if (!$toPlural && array_key_exists($word, self::$synonyms))
            return self::$synonyms[$word];
        
        if ($toPlural && array_key_exists($word, self::$plurals))
            return self::$plurals[$word];

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