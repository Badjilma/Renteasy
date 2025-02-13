<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantRoom extends Model
{
    protected $fillable = [
        'tenant_id',
        'room_id'
    ];
}
