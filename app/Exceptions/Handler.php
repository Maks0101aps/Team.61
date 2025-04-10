<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Set the application locale based on the user's preference
        $language = Session::get('language', 'uk');
        App::setLocale($language);
        
        // Log the exception with context
        Log::error('Exception: ' . get_class($e), [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'language' => $language,
        ]);
        
        // Translate common method not found errors
        if ($e instanceof \BadMethodCallException) {
            $message = $e->getMessage();
            if (strpos($message, 'Method') !== false && strpos($message, 'does not exist') !== false) {
                // Example: "Method App\Http\Controllers\EventsController::calendar does not exist."
                if ($language === 'uk') {
                    // Ukrainian translation
                    $message = str_replace('Method', 'Метод', $message);
                    $message = str_replace('does not exist', 'не існує', $message);
                    $message = preg_replace('/Did you mean (.*?)\?/', 'Можливо, ви мали на увазі $1?', $message);
                    
                    $e = new \BadMethodCallException($message);
                }
            }
        }
        
        return parent::render($request, $e);
    }
} 