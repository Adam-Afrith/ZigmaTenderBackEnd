<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_no',
        'customer_category',
        'customer_name',
        'smart_city',
        'customer_sub_category',
        'country',
        'state',
        'district',
        'city',
        'pincode',
        'address',
        'phone',
        'pan',
        'mobile_no',
        'current_year_date',
        'email',
        'gst_registered',
        'gst_no',
        'population_year_data',
        'website',
        'createdby_userid',
        'updatedby_userid',
    ];

    protected $attributes = [ 
        'delete_status'=> 0,
    ]; 

    public function countrys()
    {
        return $this->belongsTo(CountryMaster::class,'country');
    }
   
    public function districts()
    {  
        return $this->belongsTo(DistrictMaster::class,'district');
    }
    public function citys()
    {
        return $this->belongsTo(CityMaster::class,'city');
    }
}
