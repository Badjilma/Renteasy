<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenanceRequest extends Model
{
    protected $fillable = [
        'description',
        'tenant_id'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
