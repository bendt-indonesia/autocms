<?php

/**
 * @param string $string
 * @param string $endString
 *
 * @return boolean
 */
if(!function_exists('endsWith')) {
    function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }
}