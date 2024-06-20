<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\role_has_permission;
use App\Models\sub_module_menu;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //
    public function store(Request $request){

        try{
            $user = Token::where("tokenid", $request->tokenid)->first();
            if($user){

                $permissions = role_has_permission::where('role_id',  $request->usertype)->delete();

                foreach($request->permission as $submenuId=>$value){
                    if($value['view'] || $value['add'] || $value['edit'] || $value['delete']){
                        $mainMenu=sub_module_menu::find($submenuId);
                        if(!$mainMenu){
                            continue;
                        }


                        $permissions = new role_has_permission;
                        $permissions->role_id               =$request->usertype;
                        $permissions->menu_modules_id       =$mainMenu['parentModuleID'];
                        $permissions->submenu_modules_id    =$submenuId;
                        $permissions->can_view              =$value['view'];
                        $permissions->can_add               =$value['add'];
                        $permissions->can_edit              =$value['edit'];
                        $permissions->can_delete            =$value['delete'];
                        $permissions->save();
                    }
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Saved SuccessFully!'
                ]);

            }else{
                throw new \Exception('Invalid Token');
            }


        }catch(\Exception $e){
            $error = $e->getMessage();
            return response()->json([
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }

    }


    public function getoptions()
    {
        //
        $roles = role_has_permission::groupBy('role_id')->select('role_id')->get();
        $ids=[];

        foreach($roles as $role){
            $ids[] =$role['role_id'];
        }

       

        $userType = role::where('activeStatus', 'active')
        ->whereNotIn('id', $ids)
        ->orderBy('id', 'asc')->get();
      
    
        if ($userType)
            return response()->json([
                'status' => 200,
                'userType' => $userType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function getPermissionList(){
        $roles = role_has_permission::groupBy('role_id')->select('role_id')->get();

        if ($roles){
            foreach($roles as $role){
                $roleName = role::find($role['role_id']);
                $role['name'] = $roleName['name'];
            }


            return response()->json([
                'status' => 200,
                'roles' => $roles
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function getSavedData($usertype){
        $permissions = role_has_permission::where('role_id', $usertype)->get();
        $roleName = role::find($usertype);

        if ($permissions){
            return response()->json([
                'status' => 200,
                'permissions' => $permissions,
                'roleName' => $roleName,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }

    }

    public function destroy($role_id)
    {
        try {
            $permissions = role_has_permission::where('role_id',  $role_id)->delete();
            if ($permissions) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.',
                    "errormessage" => "",
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();

            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error
            ]);
        }
    }

    public function PermissionMasterTable(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        if($userid)
        {
            $tableName = 'roles';
            $header=['User Type (Role)'];
            $specificColumns = ['name'];
            $columnNames = DB::select("SHOW COLUMNS FROM $tableName");
            $filteredColumns = array_intersect($specificColumns, array_column($columnNames, 'Field'));
            $roles = role_has_permission::groupBy('role_id')->select('role_id')->get();

            if ($roles)
            {
                foreach($roles as $role)
                {
                    $roleName = role::find($role['role_id']);
                    $role['name'] = $roleName['name'];
                }
    
    
                return response()->json([
                    'data'=>$roles,
                    'header'=>$header,
                    'title'=>'User Permission Master',
                    'accessor'=> $filteredColumns,
                ]);
            }
        }
       
    }
}
