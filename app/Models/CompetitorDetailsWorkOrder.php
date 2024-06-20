<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorDetailsWorkOrder extends Model
{
    use HasFactory;
    protected $fillable = ['compId','compNo','custName','projectName','tnederId','state','woDate','quantity','unit','projectValue','perTonRate','qualityCompleted','date','woFile','woFileFiletype','completionFile','completionFileType','cr_userid','edited_userid'];
}
