<?php

use App\Console\HZip;
use App\Console\ProgressBar;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('seed', function () {
    $progressBar = new ProgressBar();

    $days = [
        '2021-04-01',
        '2021-04-02',
        '2021-04-03',
        '2021-04-04',
    ];

    $directory = storage_path('logs/daily');

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    foreach ($days as $d => $day) {
        $progressBar->progress($d / 4);
        $file = fopen("$directory/$day.log", 'w');
        $count = mt_rand(300000, 900000);

        for ($i = 0; $i < $count; $i++) {
            $date = CarbonImmutable::parse("$day UTC");
            $microseconds = round(24 * 3600 * 1000000 * $i / $count);
            $seconds = floor($microseconds / 1000000);
            $microseconds %= 1000000;
            fwrite($file,
                CarbonImmutable::parse("$day UTC")
                    ->modify("$seconds seconds $microseconds microseconds")
                    ->format('Y-m-d H:i:s.u') .
                str_pad(
                    mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255),
                    20,
                    ' ',
                    STR_PAD_LEFT
                ) .
                "\n"
            );
            $progressBar->progress(($d + ($i + 1) / $count) / 4);
        }

        fclose($file);
    }

    echo "\n";
})->purpose('Seed cache directory with random data');

Artisan::command('zip', function () {
    HZip::zipDir(realpath(__DIR__ . '/../app'), 'app.zip');
})->purpose('Zip the app directory to send the solution');
