<?php
/**
 * Book model
 *
 * PHP version 7
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Models;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * Main Book class
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class Book extends Model
{
    protected $fillable = [
        'name', 'isbn', 'country',
        'number_of_pages', 'publisher', 'release_date'
    ];
    
    protected $hidden = [ 'created_at', 'updated_at', ];

    /**
     * A book has many authors
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function getAuthors()
    {
        return $this->hasManyThrough(
            Author::class, BookAuthor::class,
            'book_id', 'id',
            'id', 'author_id'
        );
    }

    /**
     * Add authors to a book
     *
     * @param array $ids Array containing IDs of authors to add to book
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return void
     */
    public function addAuthors(array $ids)
    {
        $book_authors = [];
        $now_time     = Carbon::now();
        
        foreach ($ids as $author_id) {
            $book_authors = [
                'author_id'  => $author_id,
                'book_id'    => $this->id,
                'created_at' => $now_time,
                'updated_at' => $now_time,
            ];
        }
        
        BookAuthor::whereBookId($this->id)->delete();
        BookAuthor::insert($book_authors);

        return $this;
    }

    /**
     * Remove authors from a book
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return void
     */
    public function removeAuthors()
    {
        BookAuthor::whereBookId($this->id)->delete();

        return $this;
    }
}
