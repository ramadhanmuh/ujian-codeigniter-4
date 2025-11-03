<?php

function active_menu($segment): string
{
    // Take the current URL, for example: admin/dashboard
    $uri = uri_string();
    
    return (strpos($uri, $segment) !== false) ? 'active' : '';
}

function generate_uuid(): string
{
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}