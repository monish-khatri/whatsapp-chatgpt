<?php

use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Twilio Webhook endpoint
Route::post('incoming-message', [ TwilioController::class, 'handleIncomingMessage']);

// Twilio callback endpoint
Route::post('send-message', [ TwilioController::class, 'sendMessage']);
