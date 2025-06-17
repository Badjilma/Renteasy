<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PublicAllController extends Controller
{
     /**
     * Afficher toutes les propriétés avec filtres et pagination
     */
    public function index(Request $request)
    {
        $query = Property::with(['rooms', 'rules']);

        // Filtre par recherche (nom ou adresse)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filtre par disponibilité
        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        // Filtre par nombre minimum de chambres
        if ($request->filled('min_rooms')) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->havingRaw('COUNT(*) >= ?', [$request->min_rooms]);
            });
        }

        // Trier par date de création (plus récentes en premier)
        $query->orderBy('created_at', 'desc');

        // Pagination
        $properties = $query->paginate(12);

        return view('properties', compact('properties'));
    }

}
