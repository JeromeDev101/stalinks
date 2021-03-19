<?php

if (! function_exists('trim_excel_special_chars')) {
    function trim_excel_special_chars($input)
    {
        return trim($input, " \t\n\r\0\x0B\xA0");
    }
}

if (!function_exists('implode_array_to_strings')) {
    function implode_array_to_strings($arrInput)
    {
        $result = [];
        if (is_array($arrInput)) {
            foreach ($arrInput as $arr) {
                $result[] .= "'". $arr ."'";
            }
        } else {
            $result[] .= "'". $arrInput ."'";
        }

        return $result;
    }
}

if (! function_exists('divnum')) {

    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }

}
