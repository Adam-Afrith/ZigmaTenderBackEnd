<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\menu_module;
use App\Models\sub_module_menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //
    public function getMenus(){
        $menuList = menu_module::with('sub_menus')
        ->get();
        
        return response()->json([
            "menuList" => $menuList
        ]);
    }

    public function getOptions(){
        $menuList = menu_module::with('sub_menus')
        ->get();
        
        return response()->json([
            "menuList" => $menuList
        ]);
    }
}
