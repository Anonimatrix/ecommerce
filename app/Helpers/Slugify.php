<?php

namespace App\Helpers;

class Slugify
{

    public static function slugify($string)
    {
        return str_replace(' ', '-', $string);
    }

    public static function slugifyReverse($string)
    {
        return str_replace('-', ' ', $string);
    }
}
