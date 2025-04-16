<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserSearchController;
use App\Http\Controllers\Api\ParentTypeController;
use App\Http\Controllers\EventsController;

Route::middleware(['auth', 'password.change'])->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search']);
    
    // Event details API endpoint
    Route::get('/events/{event}', [EventsController::class, 'apiGetEvent']);
});

Route::get('/parent-type/{user}', [ParentTypeController::class, 'getParentType']); 