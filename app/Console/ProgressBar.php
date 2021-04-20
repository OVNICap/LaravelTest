<?php

declare(strict_types=1);

namespace App\Console;

final class ProgressBar
{
    private $previousRatio = null;

    private $barLength = 50;

    public function progress($ratio): void
    {
        $ratio = round(100 * $ratio);

        if ($ratio === $this->previousRatio) {
            return;
        }

        $percent = str_pad("$ratio", 3, ' ', STR_PAD_LEFT);
        $length = (int) round($this->barLength * $ratio / 100);
        $fill = "\033[1;34m" . str_repeat('█', $length) . "\033[0m";
        $empty = "\033[1;30m" . str_repeat('░', $this->barLength - $length) . "\033[0m";

        echo "\r$percent % $fill$empty";
    }
}
