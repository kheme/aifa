<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $api_url;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api_url = env('EXTERNAL_API_URL');
    }

    /**
     * Sends a success json response to the user
     *
     * @param array $data (Optional) Array of data to include in the response. Defaults to [].
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    protected function successRespons(array $data = []) : \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'status_code' => 200,
                'status'      => 'success',
                'data'        => $data,
            ],
            200
        );
    }

    /**
     * Sends a success json response to the user
     *
     * @param array $error_message (Optional) Message to include in the response. Defaults to null.
     * @param array $status_code   (Optional) Status code for the response. Defaults to 400.
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    protected function errorRespons(string $error_message = null, int $status_code = 400) : \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'status_code' => $status_code,
                'status'      => 'failure',
                'data'        => [ 'message' => $error_message, ],
            ],
            $status_code
        );
    }
}
