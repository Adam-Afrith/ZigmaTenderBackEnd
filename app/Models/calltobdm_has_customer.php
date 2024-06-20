<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calltobdm_has_customer extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(CustomerCreationProfile::class, 'customer_id');
    }

    public function calltobdm()
    {
        return $this->belongsTo(calltobdm::class, 'calltobdm_id');
    }
}
