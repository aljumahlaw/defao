<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'user.active'])->group(function () {
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
    
    Route::get('/documents/archive', function () {
        return view('documents.archive');
    })->name('documents.archive');
    
    Route::get('/workflow', function () {
        return view('workflow.index');
    })->name('workflow.index');
    
    Route::get('/archive', function () {
        return view('archive.index');
    })->name('archive.index');
    
    Route::view('/reports', 'reports.index')->name('reports.index');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/profile/settings', function () {
        return view('profile');
    })->name('profile.settings');
    
    Route::get('/admin/users', \App\Livewire\Admin\UserManagement::class)
    ->middleware('admin')
    ->name('admin.users');
});

Route::middleware(['auth', 'throttle:10,1'])->get('/download-pdf/{key}', function (string $key) {
    $data = Cache::get($key);

    if (!$data || !isset($data['html'])) {
        abort(404, 'PDF expired');
    }

    // ✅ Extract filename from cache
    $filename = $data['filename'];

    // ✅ Auto-cleanup
    Cache::forget($key);

    $pdf = Pdf::loadHTML($data['html'])
        ->setPaper('a4', 'landscape')
        ->setOptions([
            'defaultFont' => 'Cairo',
            'isHtml5ParserEnabled' => true
        ]);

    return $pdf->download($filename);  // ✅ Synced filename
})->name('documents.download-pdf');

require __DIR__.'/auth.php';
