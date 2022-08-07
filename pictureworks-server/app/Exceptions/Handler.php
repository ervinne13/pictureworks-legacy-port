<?php

namespace App\Exceptions;

use App\Helpers\User\Comment\CommentSaveException;
use App\Helpers\User\Comment\CommentSaveExceptionCode;
use App\Helpers\Validation\LegacyValidationFailedException;
use App\Helpers\Validation\LegacyValidationFailedExceptionCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (LegacyValidationFailedException $e, $request) {
            $code = 422;
            if ($e->getCode() == LegacyValidationFailedExceptionCode::INVALID_PASS) {
                $code = 401;
            }

            return response($e->getMessage(), $code);
        });

        $this->renderable(function (CommentSaveException $e, $request) {
            $code = 500;
            if ($e->getCode() == CommentSaveExceptionCode::UNREGISTERED_USER) {
                $code = 404;
            }

            return response($e->getMessage(), $code);
        });
    }
}
