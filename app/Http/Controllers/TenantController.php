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
        $tenants = Tenant::with(['contract', 'rooms', 'maintenanceRequests'])->get();
        return view('ownersite.tenants.alltenants', compact('tenants'));
    }
    public function create()
    {
        return view('ownersite.tenants.addtenants');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'cni' => 'required|string|unique:tenants',
        ]);

        $tenant = Tenant::create($request->all());

        return redirect()->route('tenants.all')->with('success', 'Locataire ajouté avec succès');
    }
    public function assignRoom(Request $request, $tenantId)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $tenant = Tenant::where('id', $tenantId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $room = Room::whereHas('property', function($query) {
            $query->where('user_id', Auth::id());
        })->where('id', $request->room_id)->firstOrFail();

        if ($room->is_rented) {
            return redirect()->back()->with('error', 'Cette chambre est déjà louée');
        }

        // Vérifier si le locataire n'a pas déjà cette chambre
        $existingAssignment = TenantRoom::where('tenant_id', $tenant->id)
            ->where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'Ce locataire a déjà cette chambre assignée');
        }

        // Créer l'assignation avec le modèle TenantRoom
        TenantRoom::create([
            'tenant_id' => $tenant->id,
            'room_id' => $room->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active'
        ]);

        $room->update(['is_rented' => true]);

        return redirect()->route('tenants.all')->with('success', 'Chambre assignée avec succès au locataire ' . $tenant->name);
    }

    // API pour récupérer les chambres disponibles
    public function getAvailableRooms()
    {
        $user_id = Auth::id();
        $rooms = Room::with('property')
            ->whereHas('property', function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('is_rented', false)
            ->get();

        return response()->json($rooms);
    }

    public function show(Tenant $tenant)
    {
        return view('ownersite.tenants.showtenants', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('ownersite.tenants.edittenants', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'cni' => 'string|unique:tenants,cni,' . $tenant->id,
        ]);

        $tenant->update($request->all());

        return redirect()->route('tenants.show', $tenant)->with('success', 'Informations du locataire mises à jour');
    }
}
