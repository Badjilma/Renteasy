<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PublicPropertyController extends Controller
{
   public function index()
    {
        $properties = Property::with(['rules', 'rooms', 'secondaryPhotos'])
            ->where('availability', true)
            ->get();

        return view('index', compact('properties'));
    }

    // Méthode pour récupérer les propriétés pour la page d'accueil
    public function getPropertiesForHome()
    {
        $properties = Property::with(['rules', 'rooms', 'secondaryPhotos'])
            ->where('availability', true)
            ->limit(8) // Limiter à 8 propriétés pour le carousel
            ->get();

        return $properties;
    }

    // Affichage détaillé d'une propriété spécifique
    // public function show($id)
    // {
    //     $property = Property::with(['rules', 'rooms', 'secondaryPhotos'])
    //         ->where('availability', true)
    //         ->findOrFail($id);

    //     return view('public.property-details', compact('property'));
    // }
}
