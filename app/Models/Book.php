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
 * @link      https://twitter.com/kheme
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Main Book class
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
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
}
