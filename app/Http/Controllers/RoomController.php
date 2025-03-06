<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Property $property)
    {
        $rooms = $property->rooms()
            ->with(['caracteristiques', 'secondaryPhotos'])
            ->get();

        return response()->json($rooms);
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'principal_photo' => 'required|image',
            'caracteristiques' => 'array',
            'secondary_photos.*' => 'image'
        ]);

        $principalPhotoPath = $request->file('principal_photo')
            ->store('rooms/principal', 'public');

        $room = $property->rooms()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'principal_photo' => $principalPhotoPath,
            'is_rented' => false
        ]);

        // Ajout des caractéristiques
        if ($request->has('caracteristiques')) {
            foreach ($request->caracteristiques as $caracteristique) {
                $room->caracteristiques()->create([
                    'message' => $caracteristique
                ]);
            }
        }

        // Ajout des photos secondaires
        if ($request->hasFile('secondary_photos')) {
            foreach ($request->file('secondary_photos') as $photo) {
                $path = $photo->store('rooms/secondary', 'public');
                $room->secondaryPhotos()->create([
                    'rooms_photo' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Chambre ajoutée avec succès',
            'room' => $room->load(['caracteristiques', 'secondaryPhotos'])
        ], 201);
    }

    public function update(Request $request, Room $room)
    {

        $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'principal_photo' => 'sometimes|image',
            'caracteristiques' => 'array',
            'secondary_photos.*' => 'image'
        ]);

        if ($request->hasFile('principal_photo')) {
            Storage::disk('public')->delete($room->principal_photo);
            $room->principal_photo = $request->file('principal_photo')
                ->store('rooms/principal', 'public');
        }

        $room->update($request->only([
            'name',
            'description',
            'price',
            'is_rented'
        ]));

        // Mise à jour des caractéristiques
        if ($request->has('caracteristiques')) {
            $room->caracteristiques()->delete();
            foreach ($request->caracteristiques as $caracteristique) {
                $room->caracteristiques()->create([
                    'message' => $caracteristique
                ]);
            }
        }

        return response()->json([
            'message' => 'Chambre mise à jour avec succès',
            'room' => $room->load(['caracteristiques', 'secondaryPhotos'])
        ]);
    }

    public function destroy(Room $room)
    {

        Storage::disk('public')->delete($room->principal_photo);
        foreach ($room->secondaryPhotos as $photo) {
            Storage::disk('public')->delete($photo->rooms_photo);
        }

        $room->delete();

        return response()->json([
            'message' => 'Chambre supprimée avec succès'
        ]);
    }
}
