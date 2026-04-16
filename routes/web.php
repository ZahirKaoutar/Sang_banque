<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\WebPageController;
use App\Http\Controllers\WebDonorController;
use App\Http\Controllers\WebCentreController;
use App\Http\Controllers\WebHopitalController;
use App\Http\Controllers\WebAdminController;

/**
 * Authentication Routes (Guest Only)
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

/**
 * Root redirect
 */
Route::get('/', function () {
    return redirect('/home');
});

/**
 * Public Pages (authenticated users)
 */
Route::middleware('auth')->group(function () {
    Route::get('/home', [WebPageController::class, 'home'])->name('home');
    Route::get('/profile/{id}', [WebPageController::class, 'profile'])->name('profile');
    Route::get('/centres', [WebPageController::class, 'donorCentres'])->name('centres');
});

/**
 * Donor Routes
 */
Route::middleware(['auth', 'role:Donor'])->group(function () {
    Route::get('/mes-dons', [WebDonorController::class, 'myDonations'])->name('mes-dons');
    Route::get('/notifications', [WebDonorController::class, 'notifications'])->name('donor.notifications');
    Route::post('/notifications/{id}/respond', [WebDonorController::class, 'respondNotification'])->name('donor.respond');
});

/**
 * AgentCentre Routes
 */
Route::middleware(['auth', 'role:AgentCentre'])->group(function () {
    Route::get('/centre/demandes', [WebCentreController::class, 'demandes'])->name('centre.demandes');
    Route::post('/centre/demandes/{id}/validate', [WebCentreController::class, 'validateRequest'])->name('centre.validate');
    Route::get('/centre/notifications', [WebCentreController::class, 'notifications'])->name('centre.notifications');
});

/**
 * AgentHopital Routes
 */
Route::middleware(['auth', 'role:AgentHopital'])->group(function () {
    Route::get('/hopital/demandes', [WebHopitalController::class, 'demandes'])->name('hopital.demandes');
    Route::post('/hopital/demandes', [WebHopitalController::class, 'store'])->name('hopital.store');
});

/**
 * Admin Routes
 */
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/centres', [WebAdminController::class, 'centres'])->name('admin.centres');
    Route::get('/admin/hopitaux', [WebAdminController::class, 'hopitaux'])->name('admin.hopitaux');
    Route::get('/admin/utilisateurs', [WebAdminController::class, 'utilisateurs'])->name('admin.utilisateurs');

    // Create Centre
    Route::get('/admin/creer-centre', [WebAdminController::class, 'createCentreForm'])->name('admin.create-centre');
    Route::post('/admin/centres', [WebAdminController::class, 'storeCentre'])->name('admin.store-centre');

    // Create Hospital
    Route::get('/admin/creer-hopital', [WebAdminController::class, 'createHopitalForm'])->name('admin.create-hopital');
    Route::post('/admin/hopitaux', [WebAdminController::class, 'storeHopital'])->name('admin.store-hopital');

    // Delete/Ban Actions
    Route::delete('/admin/centres/{id}', [WebAdminController::class, 'deleteCentre'])->name('admin.delete-centre');
    Route::delete('/admin/hopitaux/{id}', [WebAdminController::class, 'deleteHopital'])->name('admin.delete-hopital');
    Route::post('/admin/users/{id}/ban', [WebAdminController::class, 'banUser'])->name('admin.ban-user');
    Route::post('/admin/users/{id}/unban', [WebAdminController::class, 'unbanUser'])->name('admin.unban-user');
});

/**
 * Logout Route (authenticated users)
 */
Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');
