<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Events (read)
    Route::get('/events',[EventController::class,'index']);
    Route::get('/events/{id}',[EventController::class,'show']);

    // Admin/Organizer manage events & tickets
    Route::middleware('role:admin,organizer')->group(function(){
        Route::post('/events',[EventController::class,'store']);
        Route::put('/events/{id}',[EventController::class,'update']);
        Route::delete('/events/{id}',[EventController::class,'destroy']);

        Route::post('/events/{event_id}/tickets',[TicketController::class,'store']);
        Route::put('/tickets/{id}',[TicketController::class,'update']);
        Route::delete('/tickets/{id}',[TicketController::class,'destroy']);
    });


});
