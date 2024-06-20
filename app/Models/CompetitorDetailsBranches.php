<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorDetailsBranches extends Model
{
    use HasFactory;
    protected $fillable = ['compId','compNo','city','country','state','district','cr_userid','edited_userid'];
}
