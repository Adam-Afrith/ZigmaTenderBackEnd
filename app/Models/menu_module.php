<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu_module extends Model
{
    use HasFactory;

    public function sub_menus(){
        return $this->hasMany(sub_module_menu::class, 'parentModuleID')->orderBy('sorting_order','asc');
    }
}
