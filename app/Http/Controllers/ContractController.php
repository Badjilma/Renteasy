<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
     public function index()
    {
        $contracts = Contract::with(['tenant', 'payments'])->get();
        return view('ownersite.contracts.allcontracts', compact('contracts'));
    }

    public function create()
    {
       $tenants = Tenant::all();
    return view('ownersite.contracts.addcontracts', compact('tenants'));
    }

   public function store(Request $request)
{
    $request->validate([
        'tenant_id' => 'required|exists:tenants,id',
        'document' => 'required|file|mimes:pdf',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'status' => 'sometimes|in:active,terminated,expired',
    ]);

    $tenant = Tenant::findOrFail($request->tenant_id);
    $documentPath = $request->file('document')->store('contracts', 'public');

    $contract = $tenant->contracts()->create([
        'document' => $documentPath,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status ?? 'active',
    ]);

    return redirect()->route('contracts.all')->with('success', 'Contrat créé avec succès');
}

    public function show(Contract $contract)
    {
        return view('ownersite.contracts.showcontract', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $tenants = Tenant::all();
        return view('ownersite.contracts.editcontracts', compact('contract', 'tenants'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'document' => 'sometimes|file|mimes:pdf',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|in:active,terminated,expired',
        ]);

        if ($request->hasFile('document')) {
            // Supprimer l'ancien document
            Storage::disk('public')->delete($contract->document);
            $contract->document = $request->file('document')->store('contracts', 'public');
        }

        $contract->update($request->except('document'));

        return redirect()->route('contracts.show', $contract)->with('success', 'Contrat mis à jour avec succès');
    }

    public function destroy(Contract $contract)
    {
        // Supprimer le fichier PDF du stockage
        if ($contract->document) {
            Storage::disk('public')->delete($contract->document);
        }

        // Supprimer le contrat de la base de données
        $contract->delete();

        return redirect()->route('contracts.all')->with('success', 'Contrat supprimé avec succès');
    }



    /**
     * Terminer un contrat
     */
    public function terminate(Contract $contract)
    {
        $contract->update([
            'status' => 'terminated',
            'end_date' => now()
        ]);

        return redirect()->back()->with('success', 'Contrat terminé avec succès');
    }
}
