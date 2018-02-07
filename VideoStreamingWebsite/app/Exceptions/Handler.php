<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $e)
    // {
    //     if($this->isHttpException($e)) {
    //         $uri = $request->path();
    //         if ($request->is('admincp/*')) {
    //           $path="admincp.";
    //         }else{
    //             $path="";
    //         }
    //         switch ($e->getStatusCode()) {

                
    //             // not authorized
    //             case 403:
    //                 return \Response::view(''.$path.'errors.403',array(),403);
    //                 break;

    //             // not found
    //             case 404:
    //                 return \Response::view(''.$path.'errors.404',array(),404);
    //                 break;

    //             // internal error
    //             case '500':
    //                 return \Response::view(''.$path.'errors.500',array(),500);
    //                 break;

    //             default:
    //                 return $this->renderHttpException($e);
    //                 break;
    //         }
    //     }
    //     else
    //     {
    //         return parent::render($request, $e);
    //     }
    // }
    // 
    public function render($request, Exception $e)
    {
        //if ($e instanceof ModelNotFoundException) {
        //    $e = new NotFoundHttpException($e->getMessage(), $e);
        //}

       // return parent::render($request, $e);
         // 404 page when a model is not found
         if ($e instanceof ModelNotFoundException) {
             return response()->view('errors.500', [], 500);
         }
         // Custom error 500 view on production
         if (app()->environment() == 'production') {
             return response()->view('errors.404', [], 404);
         }
         return parent::render($request, $e);
    }
}
