<?php

if (!function_exists('dd')) {
    function dd(...$var)
    {
        foreach ($var as $data) {
            var_dump($data);
        }
        die;
    }
}
