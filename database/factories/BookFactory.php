<?php
/**
 * Book factory
 *
 * PHP version 7
 *
 * @category  Factory
 * @package   Database\factories
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(
    Book::class,
    function (Faker $faker) {
        return [
            'name'            => $faker->sentence(mt_rand(3, 6)),
            'isbn'            => $faker->ean13(),
            'number_of_pages' => mt_rand(10, 1000),
            'publisher'       => $faker->company,
            'country'         => $faker->country,
            'release_date'    => $faker->date('Y-m-d')
        ];
    }
);