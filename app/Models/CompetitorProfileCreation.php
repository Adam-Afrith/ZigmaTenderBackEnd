<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorProfileCreation extends Model
{
    use HasFactory;
    protected $fillable = [
        'compNo', 'compName',
         'registrationType', 'registerationYear','country','state','district','city','address','pincode','panNo','mobile','email','gstNo','directors','companyType','manpower','cr_userid','edited_userid'
    ];
    
}
