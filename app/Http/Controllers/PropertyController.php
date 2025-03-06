<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    //affichage de la page d'accueil avec recupérations des règles, chambres et photos secondaires
    public function index()
    {

        try {
            $user_id = Auth::id();
            $properties = Property::with(['rules', 'rooms', 'secondaryPhotos'])
                ->where('user_id', $user_id)
                ->get();

            return response()->json($properties);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des propriétés'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'description' => 'required|string',
                'principal_photo' => 'required|image',
                'rules' => 'array',
                'secondary_photos.*' => 'image'
            ]);

            $principalPhotoPath = $request->file('principal_photo')
                ->store('properties/principal', 'public');
            $user_id = Auth::id();
            $property = Property::create([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'principal_photo' => $principalPhotoPath,
                'availability' => true,
                'user_id' => $user_id
            ]);

            // Enregistrement des règles
            if ($request->has('rules')) {
                foreach ($request->rules as $rule) {
                    $property->rules()->create(['title' => $rule]);
                }
            }

            // Enregistrement des photos secondaires
            if ($request->hasFile('secondary_photos')) {
                foreach ($request->file('secondary_photos') as $photo) {
                    $path = $photo->store('properties/secondary', 'public');
                    $property->secondaryPhotos()->create([
                        'properties_photo' => $path
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création de la propriété'], 500);
        }
    }

    public function update(Request $request, Property $property)
    {
        // $this->authorize('update', $property);

        $request->validate([
            'name' => 'string|max:255',
            'address' => 'string',
            'description' => 'string',
            'principal_photo' => 'sometimes|image',
            'rules' => 'array',
            'secondary_photos.*' => 'image'
        ]);

        if ($request->hasFile('principal_photo')) {
            Storage::disk('public')->delete($property->principal_photo);
            $property->principal_photo = $request->file('principal_photo')
                ->store('properties/principal', 'public');
        }

        $property->update($request->only([
            'name',
            'address',
            'description',
            'availability'
        ]));

        // Mise à jour des règles
        if ($request->has('rules')) {
            $property->rules()->delete();
            foreach ($request->rules as $rule) {
                $property->rules()->create(['title' => $rule]);
            }
        }

        // Ajout de nouvelles photos secondaires
        if ($request->hasFile('secondary_photos')) {
            foreach ($request->file('secondary_photos') as $photo) {
                $path = $photo->store('properties/secondary', 'public');
                $property->secondaryPhotos()->create([
                    'properties_photo' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Propriété mise à jour avec succès',
            'property' => $property->load(['rules', 'secondaryPhotos'])
        ]);
    }

    public function destroy(Property $property)
    {
        // $this->authorize('delete', $property);

        // Suppression des fichiers de photos
        Storage::disk('public')->delete($property->principal_photo);
        foreach ($property->secondaryPhotos as $photo) {
            Storage::disk('public')->delete($photo->properties_photo);
        }

        $property->delete();

        return response()->json([
            'message' => 'Propriété supprimée avec succès'
        ]);
    }
}
