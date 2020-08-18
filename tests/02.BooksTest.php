<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksTest extends TestCase
{
    /**
     * Can we books successfully?
     *
     * @return void
     */
    public function testCanCreateBook()
    {
        $book_data = [
            'name'            => 'My First Book',
            'isbn'            => '123-3213243567',
            'authors'         => [ 'John Doe' ],
            'number_of_pages' => 350,
            'publisher'       => 'Acme Books',
            'country'         => 'United States',
            'release_date'    => '2019-08-01',
        ];

        $response = $this->json('POST', 'api/v1/books', $book_data);
        $response->assertResponseStatus(200);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($book_data);
    }
}
