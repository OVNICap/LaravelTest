<?php

namespace Tests\Feature;

use App\Services\CounterService;
use Tests\TestCase;

class ChartTest extends TestCase
{
    public function testIndexActionCanBeAccessed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $directory = storage_path('logs/daily');

        $this->assertStringContainsString(
            'data-hits="' . htmlspecialchars(json_encode([
                '2021-04-01' => count(file("$directory/2021-04-01.log")),
                '2021-04-02' => count(file("$directory/2021-04-02.log")),
                '2021-04-03' => count(file("$directory/2021-04-03.log")),
                '2021-04-04' => count(file("$directory/2021-04-04.log")),
            ])) . '"',
            $response->getContent()
        );
    }

    public function testIndexActionCanBeParametrized(): void
    {
        $response = $this->get('/?start=2021-04-02&end=2021-04-03');

        $response->assertStatus(200);

        $directory = storage_path('logs/daily');

        $this->assertStringContainsString(
            'data-hits="' . htmlspecialchars(json_encode([
                '2021-04-02' => count(file("$directory/2021-04-02.log")),
                '2021-04-03' => count(file("$directory/2021-04-03.log")),
            ])) . '"',
            $response->getContent()
        );
    }

    /**
     * @group performances
     */
    public function testIndexActionPerformance(): void
    {
        /** @var CounterService $service */
        $service = app(CounterService::class);
        $directory = storage_path('logs/daily');

        // Evaluate machine performances indexed on non-optimized code
        $baseTime = microtime(true);

        $d1 = count(file("$directory/2021-04-01.log"));
        $d2 = count(file("$directory/2021-04-02.log"));
        $d3 = count(file("$directory/2021-04-03.log"));
        $d4 = count(file("$directory/2021-04-04.log"));

        $baseTime = microtime(true) - $baseTime;

        // Expect the new code to be at least 100 times faster
        $speed = 100;

        $consumed = 0;

        for ($i = 0; $consumed < 1; $i++) {
            $m = microtime(true);
            $this->assertSame($d1, $service->countLines("$directory/2021-04-01.log"));
            $this->assertSame($d2, $service->countLines("$directory/2021-04-02.log"));
            $this->assertSame($d3, $service->countLines("$directory/2021-04-03.log"));
            $this->assertSame($d4, $service->countLines("$directory/2021-04-04.log"));
            $consumed += microtime(true) - $m;
        }

        $this->assertGreaterThan(
            $speed / $baseTime,
            $i,
            'Controller::index should be at least ' . round(2000 / $i) . ' times faster'
        );
    }
}
