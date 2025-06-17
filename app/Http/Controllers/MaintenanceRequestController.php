<?php

namespace App\Http\Controllers;

use App\Models\MantenanceRequest;
use App\Models\Property;
use App\Models\Room;
use App\Models\Tenant;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Afficher le formulaire de demande de maintenance
    public function create()
    {
        // Récupérer les locataires avec leurs propriétés et chambres associées
        $tenants = Tenant::with(['rooms.property'])->get();
        $properties = Property::all();
        // Charger toutes les chambres avec leurs propriétés
        $rooms = Room::with('property')->get();

        return view('maintenance', compact('tenants', 'properties', 'rooms'));
    }

    // Stocker la demande de maintenance
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'room_id' => 'required|exists:rooms,id',
            'description' => 'required|string|min:10',
        ], [
            'tenant_id.required' => 'Veuillez sélectionner un locataire',
            'property_id.required' => 'Veuillez sélectionner une propriété',
            'room_id.required' => 'Veuillez sélectionner une chambre',
            'description.required' => 'Veuillez décrire le problème',
            'description.min' => 'La description doit contenir au moins 10 caractères',
        ]);

        // Vérifier que la chambre appartient bien à la propriété
        $room = Room::where('id', $request->room_id)
                   ->where('property_id', $request->property_id)
                   ->first();

        if (!$room) {
            return back()->withErrors(['room_id' => 'Cette chambre n\'appartient pas à la propriété sélectionnée']);
        }

        // Créer la demande de maintenance
        $maintenanceRequest = MantenanceRequest::create([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'room_id' => $request->room_id,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        // Notifier le propriétaire
        $this->notificationService->notifyOwnerOfMaintenanceRequest($maintenanceRequest);

        return redirect()->route('maintenance.create')->with('success', 'Votre demande de maintenance a été envoyée avec succès. Le propriétaire sera notifié.');
    }

    // Les autres méthodes restent inchangées...

    // Afficher toutes les demandes pour les propriétaires
    public function index()
    {
        $requests = MantenanceRequest::with(['tenant', 'property', 'room'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('ownersite.maintenance.receive_maintenance', compact('requests'));
    }

    // Afficher les demandes pour une propriété spécifique
    public function indexByProperty($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        $requests = MantenanceRequest::with(['tenant', 'room'])
                                   ->where('property_id', $propertyId)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('maintenance.property', compact('requests', 'property'));
    }

    // Mettre à jour le statut d'une demande
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $maintenanceRequest = MantenanceRequest::findOrFail($id);
        $oldStatus = $maintenanceRequest->status;

        $maintenanceRequest->update(['status' => $request->status]);

        // Notifier le locataire du changement de statut
        $this->notificationService->notifyTenantOfStatusChange($maintenanceRequest, $oldStatus);

        return back()->with('success', 'Statut mis à jour avec succès');
    }
}
