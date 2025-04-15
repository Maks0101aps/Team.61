<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
    ];

    protected $dontReport = [
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

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