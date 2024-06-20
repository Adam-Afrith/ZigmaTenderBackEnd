<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDetailsProsCons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;


class CompetitorDetailsProsConsController extends Controller
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

        // $existence = CompetitorDetailsProsCons::where("compNo", $request->compNo)
        //     ->where("compId", $request->compId)
        //     ->where("strength", $request->strength)
        //     ->where("weakness", $request->weakness)
        //     ->select("*");
        
        
        // if ($existence) {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Already Exists!',
        //         "existence"=>$existence
        //     ]);
        // }

        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','strength'=>'nullable|string','weakness'=>'nullable|string', 'cr_userid'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                // 'message' =>"Not able to Add Strength/Weakness details now..!",
                'message' => $validator->messages(),
            ]);
        }
        $pros_cons = CompetitorDetailsProsCons::firstOrCreate($request->all());
        if ($pros_cons) {
            return response()->json([
                'status' => 200,
                'message' => 'Added Succssfully!',
            ]);
        }
    }

    
    public function show($id)
    {
        $pros_cons= CompetitorDetailsProsCons::find($id);
        if ($pros_cons)
            return response()->json([
                'status' => 200,
                'pros_cons' => $pros_cons
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

        // $pros_cons = CompetitorDetailsProsCons::where('compId',$request->compId)
        // ->where('compNo', $request->compNo)
        // ->where('strength',$request->strength)
        // ->orWhere('weakness',$request->weakness)
        // ->where('id', '!=', $id)
        // ->exists();
        // if ($pros_cons) {
        //     return response()->json([
        //         'status' => 404,
        //         'errors' => 'Strength/Weakness Already Exists'
        //     ]);
        // }
        // $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','strength'=>'string','edited_userid'=>'required|integer']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 404,
        //         'errors' => $validator->messages(),
        //     ]);
        // }


        $pros_cons = CompetitorDetailsProsCons::findOrFail($id)->update($request->all());
        if ($pros_cons)
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
            $pros_cons = CompetitorDetailsProsCons::destroy($id);
            if($pros_cons)    
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


    public function getProsConsList($compid)
    {
        $pros_cons = CompetitorDetailsProsCons::where("compId",$compid)
        ->select('id', 'compNo', 'compId', 'strength', 'weakness')
        ->orderBy('id','DESC')
        ->get();

    if ($pros_cons)
        return response()->json([
            'status' => 200,
            'pros_cons' => $pros_cons
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }

}
