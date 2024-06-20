<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sub_module_menu extends Model
{
    use HasFactory;

    public function permissions(){
        return $this->hasMany(role_has_permission::class, 'submenu_modules_id');
    }
}
