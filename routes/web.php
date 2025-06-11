<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PublicPropertyController;
use App\Http\Controllers\PublicRoomController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes publiques pour l'affichage des propriétés
Route::get('/propertiespublic', [PublicPropertyController::class, 'index'])->name('public.properties.all');
Route::get('/property/{id}', [PublicPropertyController::class, 'show'])->name('public.property.show');
// Routes publiques pour les chambres
Route::prefix('propertiespublic/{property}')->group(function () {
    // Liste des chambres d'une propriété
    Route::get('/roomsall', [PublicRoomController::class, 'index'])
        ->name('public.rooms.index');

    // Détails d'une chambre
    Route::get('/roomsall/{room}', [PublicRoomController::class, 'show'])
        ->name('public.rooms.show');
});

/////////////les routes pour le site de owner//////////////////
Route::get('/indexOwner', [DashboardController::class, 'index'])->name('accueilindex');


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

Route::get('/properties', function () {
    return 'Properties route works';
})->name('properties.all');

Route::get('/propertiesall', [PropertyController::class, 'index'])->name('properties.all');
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
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.all');
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
    Route::get('/tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
    // Routes pour la gestion des locataires
    Route::post('tenants/{tenant}/assign-room', [TenantController::class, 'assignRoom'])->name('tenants.assign-room');
    Route::put('tenants/end-rental/{pivotId}', [TenantController::class, 'endRental'])->name('tenants.end-rental');

// Contrats
Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.all');
Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
Route::post('/contractstore', [ContractController::class, 'store'])->name('contracts.store');
Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
// Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])->name('contracts.download');
Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');

    // Demandes de maintenance
    Route::get('/maintenance-requests', [MaintenanceRequestController::class, 'index']);
    Route::post('/tenants/{tenant}/maintenance-requests', [MaintenanceRequestController::class, 'store']);
    // Route pour la mise à jour des demandes de maintenance (commentée dans votre contrôleur)
    // Route::put('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);


    // Ajouter ces routes dans votre fichier routes/api.php
    Route::get('/properties', function () {
        return App\Models\Property::all(['id', 'name']);
    });

    Route::get('/properties/{property}/available-rooms', function ($propertyId) {
        return App\Models\Room::where('property_id', $propertyId)
            ->where('is_rented', false)
            ->get(['id', 'name', 'price']);
    });
});
