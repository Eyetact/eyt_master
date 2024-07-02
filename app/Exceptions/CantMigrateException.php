<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CantMigrateException extends Exception
{
    protected $message = 'can`t make migration to the database';
    protected $code = 502;

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // log the error into the log channel(files, ..)
        Log::info($this->getMessage());
    }
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // display the error to the view (or api depending on the request type)
        // if the the request comes from api (using the prefix) then return response json
        if ($request->is('api/*') || $request->ajax())
            return response()->json(['status' => false, 'message' => $this->getMessage(), 'code' => $this->getCode()], $this->getCode());
        // else redirect to the error view  
        return response()->view('errors.custom', [$this->getMessage()], $this->getCode());
    }
}
