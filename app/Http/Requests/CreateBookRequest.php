<?php
/**
 * Form request validators for POST /api/v1/books
 *
 * PHP version 7
 *
 * @category  Validator
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2019 Datespace. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

/**
 * Main CreateBookRequest class
 *
 * @category  Validator
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2019 Datespace. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class CreateBookRequest
{
    protected $validator;
    /**
     * Request validation for UserController@register
     * 
     * @param Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Validation\Validator
     */
    public function validate(Request $request) : ValidationValidator
    {
        $this->validator = Validator::make($request->all(), [
            'name'            => 'required',
            'isbn'            => 'required',
            'authors.*'       => 'required',
            'number_of_pages' => 'required|integer',
            'publisher'       => 'required',
            'country'         => 'required',
            'release_date'    => 'required|date_format:Y-m-d',
        ],
        [
            'name.required'            => 'Please enter a book name',
            'isbn.required'            => 'Pelase enter an ISBN',
            'authors.*.required'       => 'Please enter at least one author',
            'number_of_pages.required' => 'Please enter the number of pages',
            'number_of_pages.integer'  => 'Number of pages must be an integer',
            'publisher.required'       => 'Please enter a publisher\' name',
            'country.required'         => 'Please enter a country',
            'release_date.required'    => 'Please enter a release date',
            'release_date.date_format' => 'Release date must be in the format YYYY-MM-DD',
        ]);

        return $this->validator;
    }

    /**
     * Return book information without authors
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    public function getBookInfo() : array
    {
        return collect($this->validator->validated())->except('authors')->toArray();
    }

    /**
     * Return list authors only
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    public function getAuthors() : array
    {
        return collect($this->validator->validated())->only('authors')->flatten()->toArray();
    }
}
