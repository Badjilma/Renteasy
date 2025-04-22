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
            ->with(['roomCaracteristic', 'roomSecondaryPhoto'])
            ->get();

        return view('ownersite.rooms.allrooms', compact('property', 'rooms'));
    }

    public function create(Property $property)
    {
        return view('ownersite.rooms.addrooms', compact('property'));
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
                $room->roomCaracteristic()->create([
                    'title' => $caracteristique
                ]);
            }
        }

        // Ajout des photos secondaires
        if ($request->hasFile('secondary_photos')) {
            foreach ($request->file('secondary_photos') as $photo) {
                $path = $photo->store('rooms/secondary', 'public');
                $room->roomSecondaryPhoto()->create([
                    'room_photo' => $path
                ]);
            }
        }

        return redirect()->route('rooms.all', $property->id)
            ->with('success', 'Chambre ajoutée avec succès');
    }

    public function show(Property $property, Room $room)
    {

        // Vérification que la chambre appartient bien à la propriété
        if ($room->property_id !== $property->id) {
            abort(404, 'Chambre non trouvée');
        }

        $room->load(['roomCaracteristic', 'roomSecondaryPhoto']);

        return view('ownersite.rooms.showrooms', compact('property', 'room'));
    }

    public function edit(Property $property, Room $room)
    {
        // Vérification que la chambre appartient bien à la propriété
        if ($room->property_id !== $property->id) {
            abort(404, 'Chambre non trouvée');
        }

        $room->load(['roomCaracteristic', 'roomSecondaryPhoto']);

        return view('ownersite.rooms.editrooms', compact('property', 'room'));
    }

    public function update(Request $request, Property $property, Room $room)
    {
        // Vérification que la chambre appartient bien à la propriété
        if ($room->property_id !== $property->id) {
            abort(404, 'Chambre non trouvée');
        }

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
            $room->roomCaracteristic()->delete();
            foreach ($request->caracteristiques as $caracteristique) {
                $room->roomCaracteristic()->create([
                    'title' => $caracteristique
                ]);
            }
        }

        return redirect()->route('rooms.all', $property->id)
            ->with('success', 'Chambre mise à jour avec succès');
    }

    public function destroy(Property $property, Room $room)
    {

        Storage::disk('public')->delete($room->principal_photo);
        foreach ($room->roomSecondaryPhoto as $photo) {
            Storage::disk('public')->delete($photo->room_photo);
        }

        $room->delete();

        return redirect()->route('rooms.all', $property->id)
            ->with('success', 'Chambre supprimée avec succès');
    }
}
