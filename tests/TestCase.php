<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $this->forceTestingEnvironment();

        $app = parent::createApplication();

        $app['config']->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
            'cache.default' => 'array',
            'queue.default' => 'sync',
            'session.driver' => 'array',
            'mail.default' => 'array',
        ]);

        return $app;
    }

    private function forceTestingEnvironment(): void
    {
        foreach ($this->testingEnvironment() as $key => $value) {
            putenv("{$key}={$value}");

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    /**
     * @return array<string, string>
     */
    private function testing_environment(): array
    {
        return [
            'APP_ENV' => 'testing',
            'BCRYPT_ROUNDS' => '4',
            'BROADCAST_CONNECTION' => 'null',
            'CACHE_STORE' => 'array',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'DB_URL' => '',
            'MAIL_MAILER' => 'array',
            'QUEUE_CONNECTION' => 'sync',
            'SESSION_DRIVER' => 'array',
        ];
    }
}
