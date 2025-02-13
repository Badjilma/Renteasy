<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'document',
        'start_date',
        'end_date',
        'tenant_id'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
