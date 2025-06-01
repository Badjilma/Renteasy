<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_rented',
        'principal_photo'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function roomSecondaryPhoto()
    {
        return $this->hasMany(RoomSecondaryPhoto::class);
    }
    public function roomCaracteristic()
    {
        return $this->hasMany(RoomCaracteristic::class);
    }
    public function rooms()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_rooms')->withTimestamps();
    }
}
