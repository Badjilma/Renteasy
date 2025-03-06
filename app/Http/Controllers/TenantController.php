<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['contract', 'rooms', 'maintenanceRequests'])->get();
        return response()->json($tenants);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'CNI' => 'required|string|unique:tenants',
        ]);

        $tenant = Tenant::create($request->all());

        return response()->json([
            'message' => 'Locataire ajouté avec succès',
            'tenant' => $tenant
        ], 201);
    }

    public function assignRoom(Request $request, Tenant $tenant, Room $room)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($room->is_rented) {
            return response()->json([
                'message' => 'Cette chambre est déjà louée'
            ], 400);
        }

        $tenant->rooms()->attach($room->id, [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active'
        ]);

        $room->update(['is_rented' => true]);

        return response()->json([
            'message' => 'Chambre assignée avec succès'
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'CNI' => 'string|unique:tenants,CNI,' . $tenant->id,
        ]);

        $tenant->update($request->all());

        return response()->json([
            'message' => 'Informations du locataire mises à jour',
            'tenant' => $tenant
        ]);
    }
}
