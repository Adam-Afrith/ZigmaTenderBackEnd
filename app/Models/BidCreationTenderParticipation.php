<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCreationTenderParticipation extends Model
{
    use HasFactory;
    protected $fillable = ['tenderparticipation', 'bidCreationMainId'];
    public function bidCreation()
    {
        return $this->belongsTo(BidCreation_Creation::class, 'bidCreationMainId');
    }
}
