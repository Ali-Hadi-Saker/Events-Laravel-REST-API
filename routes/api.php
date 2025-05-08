<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('events', EventController::class);

//Laravel will only look for an attendee that belongs to the given event
//this route require 2 parameters (event and attendee)
Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped();//every attendee is part of an event