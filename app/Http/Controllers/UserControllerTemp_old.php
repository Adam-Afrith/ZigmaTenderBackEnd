<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
// use App\Models\UserCreation;
use App\Models\User;

use App\Models\Token;
use Illuminate\Support\Facades\Auth;


class UserControllerTemp extends Controller
{
    function login1(Request $req)
    {
        $milliseconds = floor(microtime(true) * 1000);
        $tokenId = bin2hex(random_bytes(16)).$milliseconds;
        
        $credentials = $req->only('name', 'password');

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
            $token -> tokenId = $tokenId;
            $token -> userid = $user['id'];
            $token -> isLoggedIn = 1;
            $token->save();

            $user = auth()->user();
            $roles= $user->roles->pluck('name');
            $permission= $user->getPermissionsViaRoles()->pluck('name');

            // $user['tokenId'] = $tokenId;
            
            // $user_resource = new UserResource(auth()->user());

            // $user_resource['tokenId'] = 
            return ([
                "msg" => "Login Successfully",
                "logStatus" => "success",
                "tokenId" => $tokenId,
                'role'=>$roles,
                'permission'=>$permission
                // 'userdetails'=> $user
            ]);
       
    }

    function logout(Request $req){

        $logout = Token::where('tokenid', $req->tokenid )
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

    function validateToken(Request $request){

        //get the user id 
        $user = Token::where('tokenid','=' ,$request->tokenid)->first();   
        
        if($user){
            return response()->json([
                'isValid' => true
            ]);
        }else{
            return response()->json([
                'isValid' => false
            ]);
        }
    }

    function getRolesAndPermissions(Request $request){
        $token = Token::where('tokenid','=' ,$request->tokenid)->first();   
      
        $userid = $token->userid; 

        $user = User::find($userid) ;

        if($user){
            $roles= $user->roles->pluck('name');
            $permission= $user->getPermissionsViaRoles()->pluck('name');
            return response()->json([
                'role'=>$roles,
                'permission'=>$permission
            ]);
        }else{
            return response()->json([
                'isValid' => false
            ],401);
        }

    }

    
}