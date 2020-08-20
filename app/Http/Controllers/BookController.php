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
 * @link      https://github.com/kheme
 */
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
 * @link      https://github.com/kheme
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
     * List books from the local database
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        $books = Book::selectRaw(
            'id, name, isbn, null AS authors, number_of_pages,
            publisher, country, release_date'
        )
        ->with('getAuthors:name')
        ->get();

        foreach ($books as $book) {
            $book->authors = $book->getAuthors()->pluck('name')->toArray();
        }

        return $this->successRespons($books->makeHidden('getAuthors')->toArray());
    }

    
    /**
     * Fetch a book from the local database
     * 
     * @param int $id Primary key of book of interest
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        $book_info = Book::findOrFail($id)->with('getAuthors:name')->first();

        return $this->successRespons(
            array_merge(
                [ 'id' => $id ], 
                $this->formatBookResponse(
                    $book_info->toArray(),
                    collect($book_info->getAuthors)->map(function ($item, $key) {
                        return collect($item)->only('name');
                    })->flatten()
                    ->toArray()
                )
            )
        );
    }


    /**
     * Create book in the local database
     *
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <omuta.okiemute@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $validator = $this->create_book_request->validate($request);

        if ($validator->fails()) {
            return $this->errorRespons($validator->errors()->all()[0], 400);
        }

        $book_info   = $this->create_book_request->getBookInfo();
        $author_info = $this->create_book_request->getAuthors();

        DB::beginTransaction();

        $book    = Book::firstOrCreate([ 'name' => $book_info['name']], $book_info);
        $authors = $this->saveAuthors($author_info);

        if (! $book) {
            DB::rollBack();
            throw new Exception('Could not create book');
        }

        if (! $book->addAuthors($authors)) {
            DB::rollBack();
            throw new Exception('Could not add author(s) to book');
        }

        DB::commit();

        return $this->successRespons([
            'book' => $this->formatBookResponse($book_info, $author_info)
        ], null, 201);
    }

    public function update(Request $request, int $id) : JsonResponse
    {
        $validator = $this->create_book_request->validate($request);

        if ($validator->fails()) {
            return $this->errorRespons($validator->errors()->all()[0], 400);
        }

        $book_info = $this->create_book_request->getBookInfo();

        Book::findOrFail($id)->update($book_info);

        return $this->successRespons(
            $this->formatBookResponse(
                $book_info,
                $this->create_book_request->getAuthors()
            ),
            'The book ' . $request->name . ' was updated successfully'
        );
    }

    /**
     * Delete a book from the local database
     *
     * @param int $id Primary key of book of interest
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        $book = Book:: findOrFail($id)->first();
        $book->delete();

        return $this->successRespons(
            [],
            'The book ' . $book->name . ' was deleted successfully',
            204
        );
    }

    /**
     * Save list of authors for a given book
     *
     * @param array $names Array containing names of authors
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    protected function saveAuthors(array $names) : array
    {
        $now_time = Carbon::now();
        
        foreach ($names as $name) {
            $authors[] = Author::updateOrCreate([
                'name' => $name,
            ],
            [
                'created_at' => $now_time,
                'updated_at' => $now_time,
            ])->id;
        }

        return $authors;
    }

    /**
     * Prepare book info in the requested format
     *
     * @param array $book_info   Array with book info
     * @param array $author_info Array of author namez
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    protected function formatBookResponse(array $book_info, array $author_info) : array
    {
        return [
            'name'            => $book_info['name'],
            'isbn'            => $book_info['isbn'],
            'authors'         => $author_info,
            'number_of_pages' => $book_info['number_of_pages'],
            'publisher'       => $book_info['publisher'],
            'country'         => $book_info['country'],
            'release_date'    => $book_info['release_date'],
        ];
    }
}
