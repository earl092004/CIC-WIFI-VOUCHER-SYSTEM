<?php

use App\Http\Controllers\StudentKioskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VisitorController;

/*
Route::get('/debug-logout', function () {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
});

Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
    ]);
});
/** */


Route::get('/', function () {
    return redirect()->route('kiosk.student');
});

Route::get('/kiosk/student', [StudentKioskController::class, 'index'])
    ->name('kiosk.student');

Route::post('/kiosk/student/verify', [StudentKioskController::class, 'verify'])
    ->name('kiosk.student.verify');






    use App\Http\Controllers\WifiVoucherController;

Route::post(
    '/kiosk/student/{student}/voucher',
    [WifiVoucherController::class, 'issueStudentVoucher']
)->name('kiosk.student.voucher.issue');

Route::get(
    '/kiosk/student/{student}/voucher',
    [WifiVoucherController::class, 'showStudentVoucher']
)->name('kiosk.student.voucher');





Route::middleware('staff')
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/visitors/create', [VisitorController::class, 'create'])
            ->name('visitors.create');

        Route::post('/visitors', [VisitorController::class, 'store'])
            ->name('visitors.store');

        Route::get('/visitors/voucher/{voucher}', [VisitorController::class, 'showVoucher'])
            ->name('visitors.voucher');
    });
