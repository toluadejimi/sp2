<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use Throwable;
use Illuminate\Support\Facades\Log;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Add exception types you want to exclude from reporting
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Write code to send an email when an exception occurs.
     *
     * @return response()
     */
//    public function sendEmail(Throwable $exception)
//    {
//        try {
//            $content = [
//                'message' => $exception->getMessage(),
//                'file' => $exception->getFile(),
//                'line' => $exception->getLine(),
//                'trace' => $exception->getTrace(),
//                'url' => request()->url(),
//                'body' => request()->all(),
//                'ip' => request()->ip()
//            ];
//
//            // Create the email message content
//            $message = "Error Message on ENKPAY APP";
//            $message .= "\n\nMessage========> " . $content['message'];
//            $message .= "\n\nLine========> " . $content['line'];
//            $message .= "\n\nFile========> " . $content['file'];
//            $message .= "\n\nURL========> " . $content['url'];
//            $message .= "\n\nIP========> " . $content['ip'];
//
//           send_notification($message);
//
//        } catch (Throwable $exception) {
//            Log::error("Error while sending exception email: " . $exception->getMessage());
//        }
//    }
}
