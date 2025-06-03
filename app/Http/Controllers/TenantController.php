<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\TenantRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
     public function index()
    {
        // Récupérer tous les locataires
        $tenants = Tenant::with(['contract', 'rooms', 'maintenanceRequests'])->get();
         $availableRooms = Room::where('is_rented', false)->get();
        return view('ownersite.tenants.alltenants', compact('tenants', 'availableRooms'));
    }

 // Dans TenantController.php
public function create()
{
    $availableRooms = Room::with(['property', 'roomCaracteristic'])
        ->where('is_rented', false)
        ->get();
    
    return view('ownersite.tenants.addtenants', compact('availableRooms'));
}

  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'cni' => 'required|string|unique:tenants',
        'room_id' => 'required|exists:rooms,id',
        'start_date' => 'required|date|after_or_equal:today',
         'end_date' => 'nullable|date|after:start_date'
    ]);

    // Création du locataire
    $tenant = Tenant::create($request->except('room_id', 'start_date'));
    
    // Affectation de la chambre
    TenantRoom::create([
        'tenant_id' => $tenant->id,
        'room_id' => $request->room_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'active'
    ]);

    // Mettre à jour le statut de la chambre
    Room::where('id', $request->room_id)->update(['is_rented' => true]);
    
    return redirect()->route('tenants.all')->with('success', 'Locataire ajouté avec succès');
}

    public function show(Tenant $tenant)
    {
        return view('ownersite.tenants.showtenants', compact('tenant'));
    }

   public function edit(Tenant $tenant)
    {
        // Charger les relations nécessaires
        $tenant->load(['rooms.property']);
        
        // Récupérer les chambres disponibles pour l'affectation
        $availableRooms = Room::with(['property'])
            ->where('is_rented', false)
            ->get();
            
        return view('ownersite.tenants.edittenants', compact('tenant', 'availableRooms'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'cni' => 'required|string|unique:tenants,cni,' . $tenant->id,
        ]);

        $tenant->update($request->only(['name', 'phone', 'cni']));
        
        return redirect()->route('tenants.show', $tenant)->with('success', 'Informations du locataire mises à jour');
    }

    /**
     * Affecter une nouvelle chambre à un locataire
     */
    public function assignRoom(Request $request, Tenant $tenant)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        // Vérifier que la chambre est disponible
        $room = Room::findOrFail($request->room_id);
        if ($room->is_rented) {
            return redirect()->back()->with('error', 'Cette chambre n\'est pas disponible');
        }

        // Créer la nouvelle affectation
        TenantRoom::create([
            'tenant_id' => $tenant->id,
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active'
        ]);

        // Mettre à jour le statut de la chambre
        $room->update(['is_rented' => true]);

        return redirect()->route('tenants.edit', $tenant)
            ->with('success', 'Chambre affectée avec succès');
    }

    /**
     * Terminer une location
     */
    public function endRental(Request $request, $pivotId)
    {
        $request->validate([
            'end_date' => 'required|date|before_or_equal:today'
        ]);

        // Trouver l'enregistrement pivot
        $tenantRoom = TenantRoom::findOrFail($pivotId);
        
        // Mettre à jour la location
        $tenantRoom->update([
            'end_date' => $request->end_date,
            'status' => 'ended'
        ]);

        // Libérer la chambre
        Room::where('id', $tenantRoom->room_id)->update(['is_rented' => false]);

        return redirect()->back()->with('success', 'Location terminée avec succès');
    }
     /**
     * Supprimer un locataire
     */
    public function destroy(Tenant $tenant)
    {
        // Vérifier qu'il n'a pas de locations actives
        $activeRentals = $tenant->rooms()->wherePivot('status', 'active')->count();
        
        if ($activeRentals > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un locataire avec des locations actives');
        }

        $tenant->delete();
        
        return redirect()->route('tenants.all')->with('success', 'Locataire supprimé avec succès');
    }
}
