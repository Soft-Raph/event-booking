<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PaymentController;
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

    // Customer bookings & payments
    Route::middleware('role:customer')->group(function(){
        Route::post('/tickets/{id}/bookings',[BookingController::class,'store'])
            ->middleware('prevent.double.booking');
        Route::get('/bookings',[BookingController::class,'index']);
        Route::put('/bookings/{id}/cancel',[BookingController::class,'cancel']);
        Route::post('/bookings/{id}/payment',[PaymentController::class,'pay']);
    });

    Route::get('/payments/{id}',[PaymentController::class,'show']);

});
