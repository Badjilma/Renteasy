<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'payment_method',
        'paymment_date',
        'contract_id'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
