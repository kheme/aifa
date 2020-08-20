<?php
/**
 * Book author factory
 *
 * PHP version 7
 *
 * @category  Factory
 * @package   Database\factories
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
 */
use App\Models\BookAuthor;
use Faker\Generator as Faker;

$factory->define(
    BookAuthor::class,
    function (Faker $faker) {
        return [
             'book_id'   => factory(App\Models\Book::class)->create()->id,
             'author_id' => factory(App\Models\Author::class)->create()->id,
        ];
    }
);
