<?php

if (! function_exists('clear_string')) {
    function clear_string(?string $string): ?string
    {
        if (is_null($string)) {
            return null;
        }

        return (string) preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
}

if (! function_exists('sanitize')) {
    function sanitize(?string $data): ?string
    {
        if (is_null($data)) {
            return null;
        }
        return clear_string($data);
    }
}
