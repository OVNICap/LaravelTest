<?php

namespace App\Http\Controllers;

use App\Services\CounterService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

final class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(CounterService $counterService): View
    {
        // TODO: Read all .log files from the directory
        $directory = storage_path('logs/daily');

        // TODO: Show only the date between start and end included
        $hits = [
            '2021-04-01' => $counterService->countLines("$directory/2021-04-01.log"),
            '2021-04-02' => $counterService->countLines("$directory/2021-04-02.log"),
            '2021-04-03' => $counterService->countLines("$directory/2021-04-03.log"),
            '2021-04-04' => $counterService->countLines("$directory/2021-04-04.log"),
        ];

        $dates = array_keys($hits);

        return view('index', [
            'hits' => $hits,
            'start' => $start ?? $dates[0],
            'end' => $end ?? end($dates),
        ]);
    }
}
