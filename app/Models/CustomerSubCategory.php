<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['customersubcategory','status'];
}
