<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/chat-demo', function () {
    return view('backend.scratch-chattermate.chat');
});
Route::match(['GET', 'POST'], '/chatss', [ChatController::class, 'chat'])->name('chatss');
