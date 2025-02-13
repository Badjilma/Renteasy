<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertySecondaryPhoto extends Model
{
    protected $fillable = [
        'property_photo',
        'property_id'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
