<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExternalSearchTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanPerformExternalBookSearch()
    {
        $response = $this->get('external-books');
        $response->assertResponseStatus(200);
        $response->seeJson([
            'name' => 'A Game of Thrones',
            'status' => 'success'
        ]);
    }
}
