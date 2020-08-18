<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExternalSearchTest extends TestCase
{
    /**
     * Can we successfully search for books?
     *
     * @return void
     */
    public function testCanPerformExternalBookSearch()
    {
        $response = $this->json('GET', 'external-books?name=the hedge knight');
        $response->assertResponseStatus(200);
        $response->seeJson([
            'name' => 'The Hedge Knight',
            'status' => 'success'
        ]);
    }

    /**
     * Can we successfully search for books?
     *
     * @return void
     */
    public function testExternalBookSearchWithoutResults()
    {
        $response = $this->json('GET', 'external-books?name=Kheme is awesome!');
        $response->assertResponseStatus(200);
        $response->seeJson([
            'status' => 'success',
            'data'   => []
        ]);
    }
}
