<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksTest extends TestCase
{
    protected $fake_books;


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

        $this->fake_books = factory(App\Models\Book::class, 5)->make();
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
        $response = $this->json('POST', 'api/v1/books', $this->fake_books[0]->toArray());
        $response->assertResponseStatus(201);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($this->fake_books[0]->toArray());
        $this->seeInDatabase('books', collect($this->fake_books[0]->toArray())->except('authors')->toArray());
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
        $this->json('POST', 'api/v1/books', $this->fake_books[1]->toArray());
        $this->json('POST', 'api/v1/books', $this->fake_books[2]->toArray());

        $response = $this->json('GET', 'api/v1/books');
        $response->assertResponseStatus(200);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($this->fake_books[1]->toArray());
        $response->seeJson($this->fake_books[2]->toArray());
    }
    
    /**
     * Can we update books successfully?
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function testCanUpdateBook()
    {
        $post_response = $this->json('POST', 'api/v1/books', $this->fake_books[3]->toArray());
        $post_response->seeJson([ 'status' => 'success' ]);

        $get_response = $this->json('GET', 'api/v1/books');
        $get_response->assertResponseStatus(200);
        $get_response->seeJson($this->fake_books[3]->toArray());
        
        $new_book_id = json_decode($get_response->response->getContent())->data[0]->id;

        $patch_response = $this->json('PATCH', 'api/v1/books/' . $new_book_id, $this->fake_books[4]->toArray());
        $patch_response->assertResponseStatus(200);
        $this->seeInDatabase('books', collect($this->fake_books[4]->toArray())->except('authors')->toArray());
    }
}
