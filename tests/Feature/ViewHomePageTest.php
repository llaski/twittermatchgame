<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewHomePageTest extends TestCase
{
    /**
     * @test
     */
    public function userCanViewTheHomePage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
