<?php

namespace Tests;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected bool $seed = true;
    protected function setUp(): void
    {
        parent::setUp();
//        DB::delete('delete from reviews');
//        DB::delete('delete from books');
        DB::delete('delete from users');
        $this->seed(UserSeeder::class);
    }

}
