<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public static function setUpBeforeClass(): void
    {
        @unlink(dirname(__DIR__) . '/bootstrap/cache/config.php');

        parent::setUpBeforeClass();
    }
}
