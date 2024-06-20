<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusFinancialEvaluations extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','techsubId','competitorId','amt','unit','least','created_by','edited_by'];
}
