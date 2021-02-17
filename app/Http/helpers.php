<?php

if (! function_exists('trim_excel_special_chars')) {
    function trim_excel_special_chars($input)
    {
        return trim($input, " \t\n\r\0\x0B\xA0");
    }
}
