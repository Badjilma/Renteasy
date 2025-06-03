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
            
        return view('ownersite.tenants.alltenants', compact('tenants', 'availableRooms'));
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
