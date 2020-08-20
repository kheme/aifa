<?php
/**
 * Controller for CRUD operations on books
 *
 * PHP version 7
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
 */
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use Carbon\Carbon;

/**
 * Main BookController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
 */
class BookController extends Controller
{
    protected $create_book_request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CreateBookRequest $request)
    {
        $this->create_book_request = $request;
    }

    /**
     * Create book
     *
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <omuta.okiemute@gmail.com>
     * 
     * @return json
     */
    public function store(Request $request)
    {
        $validator = $this->create_book_request->validate($request);

        if ($validator->fails()) {
            return $this->errorRespons($validator->errors()->all()[0], 400);
        }

        $book_info = $this->create_book_request->getBookInfo();

        try {
            DB::beginTransaction();

            $book = Book::updateOrCreate(
                [ 'name' => $book_info['name'] ],
                $book_info
            );

            $updated_authors = $this->saveBookAuthors(
                $book->id,
                $this->create_book_request->getAuthors()
            );
            
            if (! $updated_authors) {
                throw new Exception('Could not create book');
            }

            DB::commit();

            $book = Book::selectRaw(
                'id, name, isbn, null AS authors, number_of_pages,
                publisher, country, release_date'
            )
            ->with('getAuthors')
            ->whereId($book->id)
            ->first();
            
            $book->authors = $book->getAuthors->pluck('name');

            return $this->successRespons($book->makeHidden([ 'id', 'getAuthors' ])->toArray());
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorRespons($exception->getMessage(), 500);
        }
    }

    /**
     * Save list of authors for a given book
     *
     * @param int   $book_id Primary key of book of interest
     * @param array $authors Array containing list of author names
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return bool
     */
    private function saveBookAuthors(int $book_id, array $authors) : bool
    {
        $book_authors = [];
        BookAuthor::whereBookId($book_id)->delete();
        foreach ($authors as $name) {
            $author = Author::updateOrCreate([ 'name' => $name ]);
            $book_authors[] = [
                'book_id'   => $book_id,
                'author_id' => $author->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return BookAuthor::insert($book_authors);
    }
}
