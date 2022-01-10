<?php

//内容纠错
function correct($string)
{
    $array = explode("'", $string);
    $string = $array[0];
    unset($array[0]);
    foreach ($array as $value) {
        $string .= "\'" . $value;
    }
    return $string;
}