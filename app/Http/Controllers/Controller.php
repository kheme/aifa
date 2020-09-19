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
     * @param array|object $data        (Optional) Array of data to include in the response. Defaults to [].
     * @param string       $message     (Optional) Message to include in response. Defaults to null.
     * @param int          $status_code (Optional) HTTP status code for the response. Defaults to 200.
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    protected function successRespons($data = [], string $message = null, int $status_code = 200) : \Illuminate\Http\JsonResponse
    {
        $response = [
            'status_code' => $status_code,
            'status'      => 'success',
        ];

        if ($message) {
            $response['message'] = $message;
        }

        $response['data'] = $data;
        
        return response()->json($response, $status_code == 204 ? 200 : $status_code);
    }

    /**
     * Sends a success json response to the user
     *
     * @param array $message     (Optional) Message to include in the response. Defaults to null.
     * @param array $status_code (Optional) HTTP status code for the response. Defaults to 400.
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    protected function errorRespons(string $message = null, int $status_code = 400) : \Illuminate\Http\JsonResponse
    {
        $response = [
            'status_code' => $status_code,
            'status'      => 'failure',
        ];

        if ($message) {
            $response['message'] = $message;
        }

        $response['data'] = [];
        
        return response()->json($response, $status_code);
    }
}
