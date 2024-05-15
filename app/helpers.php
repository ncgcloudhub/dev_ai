<?php

function calculateCredits($resolution, $quality)
{
    switch ($resolution) {
        case '512x512':
                return 1;
        case '1024x1024':
            if ($quality === 'hd') {
                return 2; 
            } else {
                return 1; 
            }
        case '1792x1024':
        case '1024x1792':
            if ($quality === 'hd') {
                return 3; 
            } else {
                return 2;
            }
        default:
            return 1;
    }
}
