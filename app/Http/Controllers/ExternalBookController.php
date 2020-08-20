<?php
/**
 * Controller for external book search on the fire & ice API
 *
 * PHP version 7
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Main ExternalBookController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <omuta.okiemute@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://twitter.com/kheme
 */
class ExternalBookController extends Controller
{
    /**
     * List books from the fire and ice database that match the given search criteria
     *
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <omuta.okiemute@gmail.com>
     * 
     * @return json
     */
    public function index(Request $request)
    {
        $intersted_columns = [
            'name', 'isbn', 'authors', 'numberOfPages',
            'publisher', 'country', 'released'
        ];

        try {
            return $this->successRespons(collect(json_decode(
                Http::get($this->api_url, [ 'name' => $request->query('name') ])))
                    ->map(function ($item, $key) use ($intersted_columns) {
                        return collect($item)->only($intersted_columns)
                    ->keyBy(function($item, $key) {
                        return Str::snake(str_replace('released', 'release_date', $key));
                    });
                })->toArray());
        } catch (\Exception $exception) {
            return $this->errorRespons($exception->getMessage(), 500);
        }
    }
}
