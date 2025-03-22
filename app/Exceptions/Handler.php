<?php

namespace App\Exceptions;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
    // If the error is about a missing page or resource
    if ($exception instanceof HttpException) {
        $code = $exception->getStatusCode();
        $message = Response::$statusTexts[$code] ?? 'Error';
        return $this->errorResponse($message, $code);
    }

    // If a database record was not found (like User not found)
    if ($exception instanceof ModelNotFoundException) {
        $model = strtolower(class_basename($exception->getModel()));
        return $this->errorResponse("No {$model} found with the given ID", Response::HTTP_NOT_FOUND);
    }

    // If validation fails (e.g., a form is missing fields)
    if ($exception instanceof ValidationException) {
        $errors = $exception->validator->errors()->getMessages();
        return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // If someone tries to access something they shouldn't
    if ($exception instanceof AuthorizationException) {
        return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
    }

    // If a user isn't logged in but tries to access protected stuff
    if ($exception instanceof AuthenticationException) {
        return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

    // If it's a development environment, show detailed error
    if (env('APP_DEBUG', false)) {
        return parent::render($request, $exception);
    }

    // For all other unknown errors
    return $this->errorResponse('Unexpected error. Please try again later.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}