<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantRoom extends Model
{
    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'status'
    ];

     protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    /**
     * Relation vers le locataire
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relation vers la chambre
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
