<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\MantenanceRequest;

class NotificationService
{
    public function notifyOwnerOfMaintenanceRequest(MantenanceRequest $maintenanceRequest)
    {
        // Récupérer le propriétaire de la propriété
        $owner = $maintenanceRequest->property->user;

        if ($owner) {
            Notification::create([
                'user_id' => $owner->id,
                'title' => 'Nouvelle demande de maintenance',
                'message' => "Une nouvelle demande de maintenance a été soumise pour la propriété {$maintenanceRequest->property->name}, chambre {$maintenanceRequest->room->room_number} par {$maintenanceRequest->tenant->first_name} {$maintenanceRequest->tenant->last_name}.",
                'type' => 'maintenance',
                'maintenance_request_id' => $maintenanceRequest->id
            ]);
        }
    }

    public function notifyTenantOfStatusChange(MantenanceRequest $maintenanceRequest, $oldStatus)
    {
        $statusMessages = [
            'pending' => 'Votre demande est en attente de traitement',
            'in_progress' => 'Votre demande est en cours de traitement',
            'completed' => 'Votre demande a été traitée avec succès',
            'cancelled' => 'Votre demande a été annulée'
        ];

        // Si le locataire a un compte utilisateur, créer une notification
        // Sinon, vous pourriez envoyer un email
        $message = $statusMessages[$maintenanceRequest->status] ?? 'Le statut de votre demande a été mis à jour';

        // Ici vous pourriez ajouter l'envoi d'email si nécessaire
        // Par exemple : Mail::to($maintenanceRequest->tenant->email)->send(new StatusChangeNotification($maintenanceRequest, $message));
    }
}
