<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreationContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_person',
        'email' ,
        'mobile_no' ,
        'designation',
        'createdby_userid',
        'updatedby_userid',
        'cust_creation_mainid'
    ];
    
    protected $attributes = [ 
        'delete_status'=> 0,
        'designation' => ''
    ]; 

}
