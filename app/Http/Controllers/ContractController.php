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
        return response()->json($contracts);
    }

    public function store(Request $request, Tenant $tenant)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $documentPath = $request->file('document')->store('contracts', 'public');

        $contract = $tenant->contract()->create([
            'document' => $documentPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'Contrat créé avec succès',
            'contract' => $contract
        ], 201);
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'document' => 'sometimes|file|mimes:pdf',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'status' => 'sometimes|in:active,terminated,expired'
        ]);

        if ($request->hasFile('document')) {
            Storage::disk('public')->delete($contract->document);
            $contract->document = $request->file('document')->store('contracts', 'public');
        }

        $contract->update($request->except('document'));

        return response()->json([
            'message' => 'Contrat mis à jour avec succès',
            'contract' => $contract
        ]);
    }
}
