<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCreationBidSubmittedStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bidSubmittedStatus',
        'modeofsubmission',
        'file_original_name',
        'file_new_name',
        'file_type',
        'file_size',
        'ext',
        'updatedby_userid'
    ];
    public function bidCreation()
    {
        return $this->belongsTo(BidCreation_Creation::class, 'bidCreationMainId');
    }

}
