<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/tasks', function () {
        return view('tasks.index');
    })->name('tasks.index');
    
    Route::get('/documents', function () {
        return view('documents.index');
    })->name('documents.index');
    
    Route::get('/documents/upload', function () {
        return view('documents.upload');
    })->name('documents.upload');
    
    Route::get('/documents/{id}', function ($id) {
        return view('documents.show', ['documentId' => $id]);
    })->name('documents.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/settings', function () {
        return view('profile');
    })->name('profile.settings');
});

require __DIR__.'/auth.php';
