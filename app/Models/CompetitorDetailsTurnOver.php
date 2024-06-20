<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorDetailsTurnOver extends Model
{
    use HasFactory;
    protected $fillable = ['compId','compNo','accountYear','accValue','cr_userid','edited_userid'];
}
