<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $status_code   = 500;
        $error_message = $exception->getMessage();

        if (strpos($error_message, '(SQL') !== false) {
            $error_message = trim(substr($error_message, 0, strpos($error_message, '(SQL')));
        }
        
        $get_file = $exception->getFile();
        $file     = substr($get_file, strpos($get_file, "app\\"));
        $file     = substr($get_file, strrpos($get_file, "\\") + 1);
        
        $error_message .= " in " . $file;
        $error_message .= ":" . $exception->getLine();
        
        $status_code = $exception->status ?? $status_code;
        
        if (method_exists($exception, 'errors')) {
            foreach ($exception->errors() as $field => $message) {
                $error_message = "$field: " . $message[0];
                break;
            }
        }

        if ($exception instanceof ModelNotFoundException) {
            $error_message = 'Record not found!';
            $status_code   = 404;
        }
        
        return response()->json(
            [
                'status_code' => $status_code,
                'status'      => 'error',
                'message'     => $error_message
            ],
            $status_code
        );
    }
}