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
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
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
        return $this->successRespons(
            BookResource::collection(Book::with('getAuthors:name')->get())
        );
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
    public function show(int $id)
    {
        $book = Book::whereId($id)->with('getAuthors:name')->first();

        return $this->successRespons(
            $book ? new BookResource($book) : []
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

        $book_info = $this->create_book_request->getBookInfo();

        DB::beginTransaction();

        $book = Book::firstOrCreate([ 'name' => $book_info['name']], $book_info);

        if (! $book) {
            DB::rollBack();
            throw new Exception('Could not create book');
        }

        if (! $book->removeAuthors()->addAuthors($this->saveAuthors($this->create_book_request->getAuthors()))) {
            DB::rollBack();
            throw new Exception('Could not add author(s) to book');
        }

        DB::commit();
        
        return $this->successRespons(new BookResource($book), null, 201);
    }

    public function update(Request $request, int $id) : JsonResponse
    {
        $validator = $this->create_book_request->validate($request);

        if ($validator->fails()) {
            return $this->errorRespons($validator->errors()->all()[0], 400);
        }
        
        DB::beginTransaction();

        Book::whereId($id)
            ->firstOrFail()
            ->removeAuthors()
            ->addAuthors($this->saveAuthors($this->create_book_request->getAuthors()))
            ->update($this->create_book_request->getBookInfo());

        DB::commit();

        return $this->successRespons(new BookResource(Book::whereId($id)->first()),
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
        $book = Book::whereId($id)->firstOrFail();
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
}
