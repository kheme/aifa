<?php
/**
 * Author factory
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
use App\Models\Author;
use Faker\Generator as Faker;

$factory->define(
    Author::class,
    function (Faker $faker) {
        return [
             'name' => $faker->firstname . ' ' . $faker->lastname,
        ];
    }
);
