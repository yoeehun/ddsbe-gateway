<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Guzzle\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();

            // Custom 404 message
            if ($code === 404) {
                return $this->errorResponse('Invalid endpoint. Please check your URL.', 404);
            }

            $message = Response::$statusTexts[$code] ?? 'HTTP error';
            return $this->errorResponse($message, $code);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->errorResponse('User not found.', Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ClientException) {
            $message = $exception->getResponse()->getBody();
            return $this->errorMessage($message, 200);
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected error. Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
