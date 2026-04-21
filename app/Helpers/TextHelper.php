<?php

namespace App\Helpers;

class TextHelper
{
    public static function chunk($text, $size = 500, $overlap = 100)
    {
        $chunks = [];
        $start = 0;

        while ($start < strlen($text)) {
            $chunks[] = substr($text, $start, $size);
            $start += ($size - $overlap);
        }

        return $chunks;
    }
}