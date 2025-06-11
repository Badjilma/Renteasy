<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupération des propriétés disponibles pour l'affichage public
        $properties = Property::with(['rules', 'rooms', 'secondaryPhotos'])
            ->where('availability', true)
            ->limit(8) // Limiter à 8 propriétés pour le carousel
            ->get();

        return view('index', compact('properties')); // Remplacez 'home' par le nom de votre vue
    }
}
