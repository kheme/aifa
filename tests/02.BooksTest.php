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
        $fake_book = array_merge(
            $this->fake_books[0]->toArray(),
            [ 'authors' => [factory(App\Models\Author::class)->make()->name]]
        );

        $response = $this->json('POST', 'api/v1/books', $fake_book);
        $response->assertResponseStatus(201);
        $response->seeJson([ 'status' => 'success' ]);
        $response->seeJson($fake_book);
        $this->seeInDatabase('books', collect($fake_book)->except('authors')->toArray());
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
        $patch_response = $this->json(
            'PATCH',
            'api/v1/books/' . factory(App\Models\Book::class)->create()->id,
            collect($this->fake_books[4]->toArray() + [ 'authors' => factory(\App\Models\Author::class)->create()->pluck('name') ])
            ->toArray()
        );

        $patch_response->assertResponseStatus(200);
        $this->seeInDatabase('books', collect($this->fake_books[4]->toArray())->except('authors')->toArray());
    }

    /**
     * Can we fetch a book successfully?
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function testCanFetchBook()
    {
        $new_book   = factory(App\Models\Book::class)->create();
        $new_author = factory(App\Models\Author::class)->create();
        
        factory(App\Models\BookAuthor::class)->create(
            [
                'book_id'   => $new_book->id,
                'author_id' => $new_author->id
            ]
        );

        $response = $this->json('GET', 'api/v1/books/' . $new_book->id);
        $response->assertResponseStatus(200);
        $response->seeJson($new_book->toArray());
        $response->seeJson([ 'authors' => [ $new_author->name ] ]);
    }

    /**
     * Can we delete books successfully?
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function testCanDeleteBook()
    {
        $new_book   = factory(App\Models\Book::class)->create();
        $new_author = factory(App\Models\Author::class)->create();
        
        factory(App\Models\BookAuthor::class)->create(
            [
                'book_id'   => $new_book->id,
                'author_id' => $new_author->id
            ]
        );

        $this->seeInDatabase('books', $new_book->toArray());
        $patch_response = $this->json('DELETE', 'api/v1/books/' . $new_book->id);
        $patch_response->assertResponseStatus(200);
        $this->missingFromDatabase('books', $new_book->toArray());
    }
}
