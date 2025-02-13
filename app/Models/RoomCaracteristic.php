<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCaracteristic extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'room_id'
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
