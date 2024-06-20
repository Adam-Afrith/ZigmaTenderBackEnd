<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDetailsLineOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;


class CompetitorDetailsLineOfBusinessController extends Controller
{
    
    
    public function index()
    {
    
    }

    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {      
        // echo "token ID:" . $curr_token=$request->tokenId;
        $user = Token::where("tokenid", $request->tokenId)->first();
        //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
        $request->request->add(['cr_userid' => $user['userid']]);
        //Here is no need of token id when insert $request into table, so remove it form $request
        $request->request->remove('tokenId');

        $existence = CompetitorDetailsLineOfBusiness::where("compNo", $request->compNo)
            ->where("compId", $request->compId)
            ->where("bizLineValue", $request->bizLineValue)->exists();
            
        if ($existence) {
            return response()->json([
                'status' => 404,
                'message' => 'Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','bizLineValue'=>'required|string', 'cr_userid'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' =>"Not able to Add Line of Business details now..!",
            ]);
        }
        $buz_line = CompetitorDetailsLineOfBusiness::firstOrCreate($request->all());
        if ($buz_line) {
            return response()->json([
                'status' => 200,
                'message' => 'Added Succssfully!',
            ]);
        }
    }

    
    public function show($id)
    {
        $buz_line= CompetitorDetailsLineOfBusiness::find($id);
        if ($buz_line)
            return response()->json([
                'status' => 200,
                'buz_line' => $buz_line
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    
    public function edit($id)
    {
    }

    
    public function update(Request $request, $id)
    {
          
        $user = Token::where("tokenid", $request->tokenId)->first();
        //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
        $request->request->add(['edited_userid' => $user['userid']]);
        //Here is no need of token id when insert $request into table, so remove it form $request
        $request->request->remove('tokenId');

        $buz_line = CompetitorDetailsLineOfBusiness::where([
            'compId' => $request->compId,
            'compNo' => $request->compNo,
            'bizLineValue'=> $request->bizLineValue,
        ])
        ->where('id', '!=', $id)
        ->exists();
        if ($buz_line) {
            return response()->json([
                'status' => 404,
                'errors' => 'Branch Already Exists'
            ]);
        }
        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','bizLineValue'=>'required|string', 'edited_userid'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'errors' => $validator->messages(),
            ]);
        }


        $buz_line = CompetitorDetailsLineOfBusiness::findOrFail($id)->update($request->all());
        if ($buz_line)
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

    public function destroy($id)
    {
        try{
            $buz_line = CompetitorDetailsLineOfBusiness::destroy($id);
            if($buz_line)    
            {
                return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!",
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!?',
                "errormessage" => "",
            ]);}
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }


    public function getLineOfBusinessList($compid)
    {
        $buz_line = CompetitorDetailsLineOfBusiness::where("compId",$compid)
        ->select('id', 'compNo', 'compId', 'bizLineValue', 'remark',)
        ->orderBy('id','DESC')
        ->get();

    if ($buz_line)
        return response()->json([
            'status' => 200,
            'buz_line' => $buz_line
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }
}
