<?php namespace App\Exceptions;

use Exception;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

// import some exceptions in order to change the renderer in production environments
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
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
    public function render($request, Exception $e)
    {

        // are we in the development phase?
        if (env('APP_DEBUG')) {
            // then return the full debugging html page
            return parent::render($request, $e);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json(['message' => 'Bad Request', 'code' => 400], 400);
        }

        return response()->json(['message' => 'Bad Request', 'code' => 500], 500);

    }

}
