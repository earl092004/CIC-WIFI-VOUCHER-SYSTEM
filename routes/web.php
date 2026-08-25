<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentKioskController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
    return view('kiosk.student');
})->name('home');

Route::middleware('auth')
    ->get('/dashboard', [StaffController::class, 'dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Volt::route('/settings/profile', 'settings.profile')
        ->name('settings.profile');

    Volt::route('/settings/password', 'settings.password')
        ->name('settings.password');

    Volt::route('/settings/appearance', 'settings.appearance')
        ->name('settings.appearance');
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

        Route::post('/vouchers/import', [StaffController::class, 'importVouchers'])
            ->name('vouchers.import');

        Route::post('/vouchers/import/preview', [StaffController::class, 'previewImport'])
            ->name('vouchers.import.preview');

        Route::post('/vouchers/import/confirm', [StaffController::class, 'confirmImport'])
            ->name('vouchers.import.confirm');

        Route::get('/vouchers', [StaffController::class, 'vouchers'])
            ->name('vouchers.index');

        Route::patch('/vouchers/{voucher}/status', [StaffController::class, 'updateVoucherStatus'])
            ->name('vouchers.status');

        Route::middleware('admin')->get('/analytics', [AnalyticsController::class, 'index'])
            ->name('analytics');

        Route::middleware('admin')->group(function () {
            Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
            Route::get('/students/create', [StudentManagementController::class, 'create'])->name('students.create');
            Route::post('/students', [StudentManagementController::class, 'store'])->name('students.store');
            Route::post('/students/import', [StudentManagementController::class, 'import'])->name('students.import');
        });

        Route::get('/visitors/create', [VisitorController::class, 'create'])
            ->name('visitors.create');

        Route::post('/visitors', [VisitorController::class, 'store'])
            ->name('visitors.store');

        Route::get('/visitors/voucher/{voucher}', [VisitorController::class, 'showVoucher'])
            ->name('visitors.voucher');

        Route::get('/visitors/vouchers', [VisitorController::class, 'vouchers'])
            ->name('visitors.vouchers');

    });
