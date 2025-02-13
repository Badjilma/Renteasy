<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'principal_photo',
        'availability',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function propertySecondaryPhoto()
    {
        return $this->hasMany(PropertySecondaryPhoto::class);
    }
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
