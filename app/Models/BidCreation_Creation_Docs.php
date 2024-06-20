<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCreation_Creation_Docs extends Model
{
    use HasFactory;
    protected $fillable = ['docname','file'];
}
