<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;

Route::resource('complaints', ComplaintController::class)->only(['store', 'show', 'index', 'create'])
    ->parameters([
        'complaints' => 'complaint_id'
    ]);

Route::patch('complaints/{complaint_id}/taken', [ComplaintController::class, 'take'])->name('complaints.take');

Route::patch('complaints/{complaint_id}/refund', [ComplaintController::class, 'refund'])->name('complaints.refund');

Route::patch('complaints/{complaint_id}/cancel', [ComplaintController::class, 'cancel'])->name('complaints.cancel');
