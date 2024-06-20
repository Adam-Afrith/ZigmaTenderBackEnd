<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

use App\Models\role_has_permission;
use App\Models\sub_module_menu;

// use Illuminate\Support\Facades\Hash;


class UserControllerTemp extends Controller
{
    function login1(Request $req)
    {


        $milliseconds = floor(microtime(true) * 1000);

        $tokenId = bin2hex(random_bytes(16)) . $milliseconds;

        $credentials = $req->only('name', 'password');


        //return "test_".$credentials;


        // $user = UserCreation::where([['user_name', $req->user_id], ['password', $req->password]])->first();
        // $user = User::where([['name', $req->user_id], ['password', $req->password]])->first();
        if (!Auth::attempt($credentials)) {
            return ([
                "msg" => "Invlaid User Name or Password",
                "logStatus" => "error"
            ]);
        }

        $user = Auth::user();

        $token = new Token;
        $token->tokenId = $tokenId;
        $token->userid = $user['id'];
        $token->isLoggedIn = 1;
        $token->save();

        $user = auth()->user();
        $roles = $user->roles->pluck('name');
        // $permission = $user->getPermissionsViaRoles()->pluck('name');
        $permission = $this->getAllPermissions($user['userType']);

        // $user['tokenId'] = $tokenId;

        // $user_resource = new UserResource(auth()->user());

        // $user_resource['tokenId'] = 
        return ([
            "msg" => "Login Successfully",
            "logStatus" => "success",
            "tokenId" => $tokenId,
            'role' => $roles,
            'permission' => $permission
            // 'userdetails'=> $user
        ]);
    }

    function logout(Request $req)
    {

        $logout = Token::where('tokenid', $req->tokenid)
            ->update([
                'isLoggedIn' => 0
            ]);

        if ($logout)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    function validateToken(Request $request)
    {

        //get the user id 
        $user = Token::where('tokenid', '=', $request->tokenid)->first();

        if ($user) {
            return response()->json([
                'isValid' => true
            ]);
        } else {
            return response()->json([
                'isValid' => false
            ]);
        }
    }

    public function index()
    {
        $userlist = DB::table('users as u')->select('u.*','m.role_id','r.name as role_name')
        ->join('model_has_roles as m','m.model_id','u.id')
        ->join('roles as r','r.id','m.role_id')
        ->get();
        
        return response()->json([
            "userlist" => $userlist
        ]);
    }


//getBdmUsersList() - Used to list bdm users list alone
    public function getBdmUsersList()
    {
        
        $user = User::where('activeStatus', 'active')
        ->whereIn('userType', function($query){
            $query->select('id')
                ->from(with(new Role)->getTable())
                ->where('name','LIKE','%BDM%')
                ->get();
        })
        ->select('id', 'userName')
        ->orderBy('id', 'asc')->get();
      
    
        if ($user)
            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    
    
    
    
    public function getoptions()
    {
        //
        $user = User::where('activeStatus', 'active')
        ->whereIn('userType', function($query){
            $query->select('id')
            ->from(with(new Role)->getTable())
            ->where('name','LIKE','%BDM%')
                ->get();
        })
        ->orderBy('id', 'asc')->get();
        
        
        if ($user)
        return response()->json([
                'status' => 200,
                'user' => $user
            ]);
            else {
                return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    
    public function store(Request $request)
    {
        //name in DB               --    Name in API payload
        //name                     --    loginId
        //userName                 --   userName
        //userType                 --   userType
        //password                 --   password
        //confirmPassword                 --   confirmPassword
        //filename(hased & stored here)                 --   file 
        //email                 --   email
try{
        $user = Token::where('tokenid', $request->tokenId)->first();
        $userid = $user['userid'];
        if ($userid) {
            $validate = $request->validate([
                'userName' => ['required'],
                'userType' => ["required"],
                'loginId' => ["required",  'unique:users,name'],
                'password' => ['required'],
                'confirmPassword' => ['required'],
                'mobile' => ['required', 'unique:users,mobile', 'regex:/[0-9]{10}/'],
                'file' => ['required'],
                'email' => ['required'],
            ]);
            if($validate)
            {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName = intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                // $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize()) / 1000;
                $ext =  $file->extension();
            }
            $userCreation = new User;
            $userCreation->name = $request->loginId;  // to be a login id
            $userCreation->userType = $request->userType;
            $userCreation->userName = $request->userName;
            $userCreation->email = $request->email;
            $userCreation->password = bcrypt($request->password);
            $userCreation->confirm_passsword = $request->password;
            $userCreation->mobile = $request->mobile;
            $userCreation->activeStatus = $request->activeStatus;
            $userCreation->filename = $fileName;
            $userCreation->original_filename = $filename_original;
            $userCreation->filesize = $filesize;
            $userCreation->fileext = $ext;
            $userCreation->createdby = $userid;
            $userCreation->save();
        }
        if ($userCreation) {

            $role = Role::find($request->userType);
            $userCreation->assignRole($role);

            return response()->json([
                "status" => 200,
                "data" => $userCreation->id
            ]);
        } else {
            return response()->json([
                "status" => 400,
                "message" => "Unable to Save !"

            ]);
        }
        }
        else{
            return response()->json([
                "status" => 400,
                "message" => "EmailId/Phone No Already Exists..!",
                "error" =>"EmailId/Phone No Already Exists..!"

            ]);
        }
    }
    catch(\Exception $ex)
    {
        return response()->json([
            "status" => 400,
            "message" => "EmailId/Phone No Already Exists..!",
            "errors" => "EmailId/Phone No Already Exists..!",
            "error" =>$ex

        ]);
    }

    }
    
    public function show($id)
    {
        
        $userCreation = User::find($id);
        if ($userCreation) {

            $userType = Role::find($userCreation->userType);
            
            $userCreation['userType'] = [
                'value' => $userType -> id,
                'label' => $userType -> name
            ];
            
            return response()->json([
                'status' => 200,
                'user' => $userCreation
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    public function getdocs($id){

        $doc = User::find($id);

        if($doc){
            $filename = $doc['filename'];
            $file = public_path()."/uploads/UserProfile/userphotos/".$filename;
            if(File::exists($file)){
                return response()->download($file);
            }else{
                return response()->json([
                    'message' => 'file not found'
                ],204);
            }
        }
    }
    
    public function update(Request $request,  $id)
    {
        
        $user = Token::where('tokenid', $request->tokenId)->first();
        $userid = $user['userid'];
        try {
            if ($userid) {
                // $validate = $request->validate([
                //     'name' => ['required'],
                //     'user_role' => ["required"],
                //     'login_id' => ["required"],
                //     'password' => ['required'],
                //     'phone' => ['required', 'regex:/[0-9]{10}/'],
                //     'photo' => ['required']
                
                // ]);

                $validate = $request->validate([
                    'userName' => ['required'],
                    'userType' => ["required"],
                    'loginId' => ["required",  'unique:users,name,'.$id ],
                    'password' => ['required'],
                    'confirmPassword' => ['required'],
                    'mobile' => ['required', 'unique:users,mobile,'.$id, 'regex:/[0-9]{10}/'],
                    'file' => ['required'],
                    'email' => ['required'],
                ]);


                if ($request->hasFile('file')) {

                    $document = User::find($id);
                    $filename = $document['filename'];
                    $file_path = public_path() . "/uploads/UserProfile/userphotos/" . $filename;

                    if (File::exists($file_path)) {
                        File::delete($file_path);
                    }

                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName = intval(microtime(true) * 1000) . $filename_original;
                    $file->storeAs('UserProfile/userphotos', $fileName, 'public');
                    $mimeType =  $file->getMimeType();
                    $filesize = ($file->getSize()) / 1000;
                    $ext =  $file->extension();
                }

                $userCreation = User::findOrFail($id);

                $userCreation->name = $request->loginId;  // to be a login id
                $userCreation->userType = $request->userType;
                $userCreation->userName = $request->userName;
                $userCreation->email = $request->email;
                $userCreation->password = bcrypt($request->password);
                $userCreation->confirm_passsword = $request->password;
                $userCreation->mobile = $request->mobile;
                $userCreation->activeStatus = $request->activeStatus;
                $userCreation->filename = $fileName;
                $userCreation->original_filename = $filename_original;
                $userCreation->filesize = $filesize;
                $userCreation->fileext = $ext;
                $userCreation->updatedby = $userid;
                $userCreation->save();
                
                if ($userCreation) {
                    
                    $role = Role::find($request->userType);
                    $userCreation->syncRoles($role);

                    return response()->json([
                        "status" => 200,
                        "message" => "Updated Successfully!"
                    ]);
                } else {

                    return response()->json([
                        "status" => 400,
                        "message" => "Unable to Update!"
                    ]);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {

            $errors = $ex->getMessage();
            
            return response()->json([
                "satatus" => 404,
                "message" => $errors
            ]);
        }
    }
    
    
    public function destroy($id)
    {
        
        try {
            $deleteuserCreation = User::destroy($id);
            if ($deleteuserCreation) {
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

    
    function getRolesAndPermissions(Request $request)
    {
        $token = Token::where('tokenid', '=', $request->tokenid)->first();
        
        $userid = $token->userid;
        
        $user = User::find($userid);
        
        if ($user) {
            $roles = $user->roles->pluck('name');
            
            $userType = $user['userType'];
            // $permissions = role_has_permission::where('role_id', $userType)->get();
            
            
            $p = $this->getAllPermissions($userType);

            
            // $permission = $user->getPermissionsViaRoles()->pluck('name');
            return response()->json([
                'role' => $roles,
                'permissions' => [] ,
                'permission' => $p,
                // 'userType' => $userType,
                // 'p' =>$p
            ]);
        } else {
            return response()->json([
                'isValid' => false
            ], 401);
        }
    }


    public function getRolehasPermission($tokenid){
        $token = Token::where('tokenid', '=', $tokenid)->first();

        $userid = $token->userid;

        $user = User::find($userid);
        if ($user) {
            $permission = role_has_permission::All();
            return response()->json([
                'permission' => $permission
            ]);
        } else {
            return response()->json([
                'isValid' => false
            ], 401);
        }
        
    }
    
    public function getAllPermissions($role){
        $userType = $role;
        // $permissions = role_has_permission::where('role_id', $userType)->get();
        $permissions = sub_module_menu::with(['permissions' => function($q) use ($userType){
            $q->where('role_id', $userType);
        }])
        ->select('id', 'name', 'aliasName')
        ->get();
        
        $p = [];
        foreach($permissions as $permission){
            if(count($permission['permissions'])){
                $p[$permission['name']] = [
                    'can_view' => $permission['permissions'][0]['can_view'],
                    'can_add' => $permission['permissions'][0]['can_add'],
                    'can_edit' => $permission['permissions'][0]['can_edit'],
                    'can_delete' => $permission['permissions'][0]['can_delete'],
                ];
            }else{
                $p[$permission['name']] = [
                    'can_view' => 0,
                    'can_add' => 0,
                    'can_edit' => 0,
                    'can_delete' => 0,
                ];  
            }
        }

        return $p;
    }

/*****************BDM LIST ONLY****************/
//This function is uesed to get BDM users only!!

    public function getBdmList()
    {
        
        $bdm_list = User::where("activeStatus", "=", "active")
        ->where('userType','2')
        ->get();
        $bdmList = [];
        foreach ($bdm_list as $row) {
            $bdmList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'bdmlist' =>  $bdmList,
        ]);
    }
/****************************************************/  
/********************EMP LIST***************************** */
public function getEmployeeList()
{
    $employee_list = User::where("activeStatus", "=", "active")
                ->where('userType','!=','1')
                ->get();
    $empList = [];
    foreach ($employee_list as $row) {
        $empList[] = ["value" => $row['id'], "label" =>  $row['name']];
    }
    return  response()->json([
        'employeelist' =>  $empList,
    ]);
}
  /***********************************************************/ 
//getbdmdetails() - Collect particular bdm user details
    public function getbdmdetails(Request $request)
    {

        try{
        $userid = Token::where('tokenid', $request->tokenid)->first()->select('userid');

        $userdetails = User::where('activeStatus', 'active')
        ->whereIn('userType', function($query){
            $query->select('id')
                ->from(with(new Role)->getTable())
                ->where('name','LIKE','%BDM%')
                ->get();
        })
        ->select('*')
        ->where('id',$request->id)
        ->orderBy('id', 'asc')->first();
      
    
        if ($userdetails)
        {
            return response()->json([
                'status' => 200,
                'user' => $userdetails
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect..!'
            ]);
        }
    }
    catch(\Exception $ex){
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect..!',
            'err'=> $ex->getMessage()
        ]);
    }

}

    public function BDMOptionsTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
            $header = ['BDM name'];
            $accessor = ['userName'];

            $user = User::where('activeStatus', 'active')
            ->whereIn('userType', function($query){
                $query->select('id')
                    ->from(with(new Role)->getTable())
                    ->where('name','LIKE','%BDM%')
                    ->get();
            })
            ->select('id', 'userName')
            ->orderBy('id', 'asc')->get();
          
        
            if ($user)
                return response()->json([
                    'status' => 200,
                    'title' => 'BDMOptions',
                    'header' => $header,
                    'accessor' => $accessor,
                    'data' => $user
                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }

        }
    }
    public function UserMasterTable(Request $request){
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        if($userid){
            $tableName = 'users';
            $header=['User Name','User Type (Role)','Mobile','E-mail','Login Id','Password','Status'];
            $specificColumns = ['userName','userType','email','mobile','name','password','activeStatus'];
            $columnNames = DB::select("SHOW COLUMNS FROM $tableName");
            $filteredColumns = array_intersect($specificColumns, array_column($columnNames, 'Field'));
            $userlist = DB::table('users as u')->select('u.*','m.role_id','r.name as role_name')
            ->join('model_has_roles as m','m.model_id','u.id')
            ->join('roles as r','r.id','m.role_id')
            ->get();

            $modifiedAccessor = array_map(function ($value)
            {
               if ($value === "userType")
                {
                   return "role_name";
               } elseif ($value === "state_id") 
               {
                   return "state_name";
               }
               return $value;
           }, $filteredColumns);
            
            return response()->json([
                'data'=>$userlist,
                'header'=>$header,
                'title'=>'User Master',
                'accessor'=> $filteredColumns,
            ]);
        }
  
    }

}
