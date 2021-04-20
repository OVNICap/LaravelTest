<?php

declare(strict_types=1);

namespace App\Services;

final class CounterService
{
    private $dateTimeLength = 26;

    private $ipLength = 20;

    public function countLines(string $file): int
    {
        // TODO: Replace with the real number of line
        return mt_rand(300000, 900000);
    }
}
