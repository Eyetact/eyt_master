<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CantCreateLabelFormException extends Exception
{
    protected $message = 'can`t create form label';
    protected $code = 500; // Adjusted to 500 for could not create something in the server-side

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // Log the error into the log channel (files, etc.)
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
        $response = [
            'status' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode()
        ];

        // Display the error as JSON if the request is from an API or AJAX
        if ($request->is('api/*') || $request->ajax()) {
            return response()->json($response, $this->getCode());
        }

        // Otherwise, return a view with the error message
        // you need to create the custom blade for errors in this case ;)
        return response()->view('errors.custom', ['error' => $this->getMessage()], $this->getCode());
    }
}
