<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    /**
     * The base URL of the application.
     *
     * @var string
     */
    public string $baseUrl;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->baseUrl = getenv('TESTING_APP_URL', 'http://localhost');

        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        // disable honeypot
        config()->set('honeypot.enabled', false);

        return $app;
    }
}
