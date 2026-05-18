<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\ChatController;

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [ChatController::class, 'sendMessage'])->name('chat.send');
    
    Route::post('/chat/private/create', [ChatController::class, 'createPrivateChat'])->name('chat.private.create');
    Route::post('/chat/group/create', [ChatController::class, 'createGroupChat'])->name('chat.group.create');
});

require __DIR__.'/auth.php';
