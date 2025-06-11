<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicRoomController extends Controller
{
     /**
     * Afficher toutes les chambres d'une propriété
     */
    public function index(Property $property)
    {
        // Vérifier que la propriété est disponible
        if (!$property->availability) {
            abort(404, 'Cette propriété n\'est pas disponible');
        }

        // Récupérer les chambres avec leurs relations
        $rooms = $property->rooms()
            ->with(['roomCaracteristic', 'roomSecondaryPhoto'])
            ->get();

        return view('rooms', compact('property', 'rooms'));
    }

    /**
     * Afficher les détails d'une chambre
     */
    public function show(Property $property, Room $room)
    {
        // Vérifier que la propriété est disponible
        if (!$property->availability) {
            abort(404, 'Cette propriété n\'est pas disponible');
        }

        // Vérifier que la chambre appartient bien à la propriété
        if ($room->property_id !== $property->id) {
            abort(404, 'Chambre non trouvée');
        }

        // Charger les relations nécessaires
        $room->load(['roomCaracteristic', 'roomSecondaryPhoto']);

        return view('room_details', compact('property', 'room'));
    }
}
