<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksTest extends TestCase
{
    protected $book_data = [];

    /**
     * Test setup
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

        $this->book_data = [
            'name'            => 'My First Book',
            'isbn'            => '123-3213243567',
            'authors'         => [ 'John Doe' ],
            'number_of_pages' => 350,
            'publisher'       => 'Acme Books',
            'country'         => 'United States',
            'release_date'    => '2019-08-01',
        ];
    }
    /**
     * Can we books successfully?
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function testCanCreateBook()
    {
        $response = $this->json('POST', 'api/v1/books', $this->book_data);
        $response->assertResponseStatus(200);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($this->book_data);
    }

    /**
     * Can we books successfully?
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function testCanListBooks()
    {
        $this->testCanCreateBook();
        $first_book = $this->book_data;

        $this->book_data = [
            'name'            => 'A Clash of Kings',
            'isbn'            => '978-0553108033',
            'authors'         => [ 'George R. R. Martin' ],
            'number_of_pages' => 768,
            'publisher'       => 'Bantam Books',
            'country'         => 'United States',
            'release_date'    => '1999-02-02',
        ];

        $this->testCanCreateBook();
        $second_book = $this->book_data;

        $response = $this->json('GET', 'api/v1/books');
        $response->assertResponseStatus(200);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($first_book);
        $response->seeJson($second_book);
    }
}
