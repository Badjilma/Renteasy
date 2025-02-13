<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'property_id'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
