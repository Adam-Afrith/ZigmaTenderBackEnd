<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorDetailsProsCons extends Model
{
    use HasFactory;
    protected $fillable = ['compId','compNo','strength','weakness','cr_userid','edited_userid'];
}
