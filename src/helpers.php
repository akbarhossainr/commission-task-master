<?php

declare(strict_types=1);

if (!function_exists('dd')) {
    function dd(...$var)
    {
        foreach ($var as $data) {
            var_dump($data);
        }
        exit;
    }
}
