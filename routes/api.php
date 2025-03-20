<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BookingPdfController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SpaceController;
use App\Http\Controllers\Api\SpaceCategoryController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Admin Routes
Route::get("/optmize", function () {
    Artisan::call("optimize");
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        if (Auth::check()) {
            $user = $request->user();
            
            // Eager load roles and permissions
            $user->load('roles.permissions');
            
    
            // Log user details
            Log::info('User API Response: ' . json_encode($user));
    
            return response()->json($user);
        }
    
        return response()->json(['message' => 'Unauthorized'], 401);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
      
    // Routes for Admins (Full Control)
    Route::middleware(['role:admin'])->group(function () {
        
        Route::apiResource('/users', UserController::class);

        Route::apiResource('space-categories', SpaceCategoryController::class);
        Route::apiResource('spaces', SpaceController::class);
        // Route::post('/spacesUpadte', [SpaceController::class, 'spacesUpadte']);
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); // Get user's bookings
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show'); // Get user's bookings
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store'); // Book a space
        Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update'); // Update booking (Admin)
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.delete');
        Route::get('/bookings/{id}/download-pdf', [BookingPdfController::class, 'downloadPDF']);
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payment/{id}/download-excel', [PaymentController::class, 'downloadExcel'])->name('payment.download.excel');
        Route::get('/payment/{id}/download-receipt', [PaymentController::class, 'downloadReceipt'])->name('payment.download.receipt');


        Route::post('/roles', [AuthController::class, 'createRole']);
        Route::post('/permissions', [AuthController::class, 'createPermission']);
        Route::get('/roles', [AuthController::class, 'allRolesWithPermissions']);
        Route::post('/assign-role', [AuthController::class, 'assignRole']);
        Route::post('/assign-permissions', [AuthController::class, 'assignPermissionsToRole']);
        Route::get('/permissions', [AuthController::class, 'allPermissions']);

    });

    // Routes for Space Management (Only Users with 'manage spaces' permission)
    Route::middleware(['permission:manage spaces'])->group(function () {
       

        Route::get('/agent-list', [SpaceController::class,'getAgentList']);
        Route::apiResource('space-categories', SpaceCategoryController::class);
        Route::apiResource('spaces', SpaceController::class);
    });
    Route::middleware(['permission:manage roles'])->group(function () {
        Route::apiResource('/users', UserController::class);

        Route::post('/roles', [AuthController::class, 'createRole']);
        Route::post('/permissions', [AuthController::class, 'createPermission']);
        Route::get('/roles', [AuthController::class, 'allRolesWithPermissions']);
        Route::post('/assign-role', [AuthController::class, 'assignRole']);
        Route::post('/assign-permissions', [AuthController::class, 'assignPermissionsToRole']);
        Route::get('/permissions', [AuthController::class, 'allPermissions']);
        Route::delete('/roles/{id}', [AuthController::class, 'deleteRole']);
    });
    Route::middleware(['permission:manage payments'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payment/{id}/download-excel', [PaymentController::class, 'downloadExcel'])->name('payment.download.excel');
        Route::get('/payment/{id}/download-receipt', [PaymentController::class, 'downloadReceipt'])->name('payment.download.receipt');
    });
    Route::middleware(['permission:manage bookings'])->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); // Get user's bookings
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show'); // Get user's bookings
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store'); // Book a space
        Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update'); // Update booking (Admin)
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.delete');
        Route::get('/bookings/{id}/download-pdf', [BookingPdfController::class, 'downloadPDF']);
    });

});




Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
 Route::post('/spacesUpadte', [SpaceController::class, 'spacesUpadte']);
 Route::get('/counts', [SpaceController::class, 'counts']);
 Route::post('/bulk-download-pdf', [BookingPdfController::class, 'downloadBulkPDF']);
 Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);



