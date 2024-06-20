<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreationSWMProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'projecttype',
        'projectvalue',
        'status' ,
        'vendortype' ,
        'vendor',
        'projectstatus',
        'duration1',
        'duration2',
        'createdby',
        'updatedby',
    ];
}
