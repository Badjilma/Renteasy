<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


/////////////les routes pour le site de owner//////////////////
Route::get('/indexOwner', function () {
    return view('ownersite.index');
})->name('accueilindex');


// Routes pour afficher les formulaires
Route::get('/register', [OwnerController::class, 'showRegisterForm'])->name('register.form');
Route::get('/login', [OwnerController::class, 'showLoginForm'])->name('login.form');

// Routes pour traiter les formulaires 
Route::post('/register', [OwnerController::class, 'register'])->name('register.attempt');
Route::post('/login', [OwnerController::class, 'login'])->name('login.connect');

/////////////les routes pour le site visiteur//////////////////

// Routes pour les propriétés
Route::middleware('auth:user')->group(function () {
    // Propriétés
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::put('/properties/{property}', [PropertyController::class, 'update']);
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy']);

    // Chambres
    Route::get('/properties/{property}/rooms', [RoomController::class, 'index']);
    Route::post('/properties/{property}/rooms', [RoomController::class, 'store']);
    Route::put('/rooms/{room}', [RoomController::class, 'update']);
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);

    // Locataires
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::put('/tenants/{tenant}', [TenantController::class, 'update']);
    Route::post('/tenants/{tenant}/rooms/{room}/assign', [TenantController::class, 'assignRoom']);

    // Contrats
    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/tenants/{tenant}/contracts', [ContractController::class, 'store']);
    Route::put('/contracts/{contract}', [ContractController::class, 'update']);

    // Demandes de maintenance
    Route::get('/maintenance-requests', [MaintenanceRequestController::class, 'index']);
    Route::post('/tenants/{tenant}/maintenance-requests', [MaintenanceRequestController::class, 'store']);
    // Route pour la mise à jour des demandes de maintenance (commentée dans votre contrôleur)
    // Route::put('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);

});
