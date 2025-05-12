<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/chat-demo', function () {
    return view('backend.scratch-chattermate.chat');
});
Route::match(['GET', 'POST'], '/chatss', [ChatController::class, 'chat'])->name('chatss');
Route::post('/save-chat', [ChatController::class, 'saveChat'])->name('save-chat');
Route::get('/get-chats', [ChatController::class, 'getChats'])->name('get-chats');
Route::get('/get-conversation/{id}', [ChatController::class, 'getConversation'])->name('get-conversation');
