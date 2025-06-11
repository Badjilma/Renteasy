<?php

namespace App\Http\Controllers;

use App\Models\MantenanceRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $requests = MantenanceRequest::with('tenant')->get();
        return response()->json($requests);
    }

    public function store(Request $request, Tenant $tenant)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $maintenanceRequest = $tenant->maintenanceRequests()->create([
            'description' => $request->description,
        ]);

        // Ici, vous pourriez ajouter une notification au propriétaire

        return response()->json([
            'message' => 'Demande de maintenance créée avec succès',
            'maintenance_request' => $maintenanceRequest
        ], 201);
    }
}
