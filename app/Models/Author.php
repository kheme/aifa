<?php
/**
 * Author model
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
 * Main Author class
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class Author extends Model
{
    protected $fillable = [ 'name', ];
    protected $hidden   = [ 'created_at', 'updated_at', 'laravel_through_key' ];
}
