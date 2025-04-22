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
Route::get('/login', [OwnerController::class, 'showLoginForm'])->name('login');

// Routes pour traiter les formulaires
Route::post('/register', [OwnerController::class, 'register'])->name('register.attempt');
Route::post('/login', [OwnerController::class, 'login'])->name('login.connect');

/////////////les routes pour le site visiteur//////////////////

// Propriétés
Route::get('/properties/create', function () {
    return view('ownersite.properties.addproperties');
})->name('properties.form');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.all');
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.create');
Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
Route::put('/properties/{id}', [PropertyController::class, 'update'])->name('properties.update');
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('properties.delete');

Route::middleware('auth')->group(function () {
    // Chambres
    Route::get('/properties/{property}/rooms', [RoomController::class, 'index'])->name('rooms.all');
    Route::get('/properties/{property}/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/properties/{property}/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/properties/{property}/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/properties/{property}/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/properties/{property}/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/properties/{property}/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.delete');
    
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
