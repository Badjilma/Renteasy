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
       $user_id = Auth::id();
       $properties = Property::with(['rules', 'rooms', 'secondaryPhotos'])
           ->where('user_id', $user_id)
           ->get();

       return view('ownersite.allproperties', compact('properties'));
   }

   public function create()
   {
       return view('ownersite.addproperties');
   }

   public function store(Request $request)
   {
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
                   'property_photo' => $path
               ]);
           }
       }

       return redirect()->route('properties.all')
           ->with('success', 'Propriété créée avec succès');
   }

   public function show($id)
   {
       $property = Property::with(['rules', 'rooms', 'secondaryPhotos'])
           ->findOrFail($id);

       // Vérification que l'utilisateur est bien le propriétaire
       if ($property->user_id !== Auth::id()) {
           abort(403, 'Accès non autorisé');
       }

       return view('ownersite.showproperty', compact('property'));
   }

   public function edit($id)
   {
       $property = Property::with(['rules', 'rooms', 'secondaryPhotos'])
           ->findOrFail($id);

       // Vérification que l'utilisateur est bien le propriétaire
       if ($property->user_id !== Auth::id()) {
           abort(403, 'Accès non autorisé');
       }

       return view('ownersite.editproperties', compact('property'));
   }

   public function update(Request $request, $id)
   {
       $property = Property::findOrFail($id);

       // Vérification que l'utilisateur est bien le propriétaire
       if ($property->user_id !== Auth::id()) {
           abort(403, 'Accès non autorisé');
       }

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
                   'property_photo' => $path
               ]);
           }
       }

       return redirect()->route('properties.all')
           ->with('success', 'Propriété mise à jour avec succès');
   }

   public function destroy($id)
   {
       $property = Property::findOrFail($id);

       // Vérification que l'utilisateur est bien le propriétaire
       if ($property->user_id !== Auth::id()) {
           abort(403, 'Accès non autorisé');
       }

       // Suppression des fichiers de photos
       Storage::disk('public')->delete($property->principal_photo);
       foreach ($property->secondaryPhotos as $photo) {
           Storage::disk('public')->delete($photo->property_photo);
       }

       $property->delete();

       return redirect()->route('properties.all')
           ->with('success', 'Propriété supprimée avec succès');
   }
}
