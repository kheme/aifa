<?php
/**
 * Model form book authors
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

use Illuminate\Database\Eloquent\Model;

/**
 * Main BookAuthor class
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class BookAuthor extends Model
{
    protected $fillable = [ 'book_id', 'author_id' ];
    protected $hidden   = [ 'laravel_through_key', 'created_at', 'updated_at', ];
}
