<?php
declare(strict_types=1);

function formatFrequency(int $times): string
{
    $mod10 = $times % 10;
    $mod100 = $times % 100;

    if ($mod10 === 1 && $mod100 !== 11) {
        $word = 'раз';
    } elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) {
        $word = 'рази';
    } else {
        $word = 'разів';
    }
    return "{$times} {$word} на тиждень";
}

function calcCompletion(int $done, int $target): float
{
    if ($target <= 0) {
        return 0.0;
    }
    return min(100.0, round($done / $target * 100, 1));
}