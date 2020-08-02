<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    { 
        if ($exception instanceof AuthenticationException) 
        {
            return response()->json(
                [
                    'errors' => [                        
                        'status' => 401,
                        'message' => $exception->getMessage(),
                    ]
                ], 401);
        }
        elseif($exception instanceof AuthorizationException) 
        {
            return response()->json(
                [
                    'errors' => [
                        'status' => 403,
                        'message' => $exception->getMessage(),
                    ]
                ], 403);
        }
        elseif($exception instanceof ModelNotFoundException)
        {
            return response()->json(
            [
                'errors' => [
                    'status' => 404,
                    'message' => $exception->getMessage(),
                ]
            ], 404);
        } 
        elseif($exception instanceof MethodNotAllowedHttpException)
        {
            return response()->json(
            [
                'errors' => [
                    'status' => 400,
                    'message' => $exception->getMessage(),
                ]
            ], 404);
        } 
        //anyone hit url from broswer return response
        elseif($exception instanceof RouteNotFoundException)
        {   
            if(!$request->expectsJson()){
                return response()->json(
                [
                    'errors' => [
                        'status' => 404,
                        'message' => 'Not Found',
                    ]
                ], 404);
            }  
            return response()->json(
            [
                'errors' => [                     
                    'status' => 404,
                    'message' => 'Not Found',
                ]
            ], 404);
        }
        elseif($exception instanceof NotFoundHttpException)
        {
            return response()->json(
            [
                'errors' => [
                    'status' => 404,
                    'message' => '404 path not found',
                ]
            ], 404);
        }
        return parent::render($request, $exception); 
    }
}
