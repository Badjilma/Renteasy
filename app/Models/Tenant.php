<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'cni'
    ];

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
    public function maintenanceRequests()
    {
        return $this->hasMany(MantenanceRequest::class);
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'tenant_rooms')->withTimestamps();
    }
}
