<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDetailsCompanyNetWorth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;


class CompetitorDetailsCompanyNetWorthController extends Controller
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

        $existence = CompetitorDetailsCompanyNetWorth::where("compNo", $request->compNo)
            ->where("compId", $request->compId)
            ->where("accountYear", $request->accountYear)->exists();
            
        if ($existence) {
            return response()->json([
                'status' => 404,
                'message' => 'Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','accountYear'=>'required|string', 'accValue'=>'required|between:1.00,99999999999.99','cr_userid'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' =>"Not able to Add Turn Over details now..!",
            ]);
        }
        $networth = CompetitorDetailsCompanyNetWorth::firstOrCreate($request->all());
        if ($networth) {
            return response()->json([
                'status' => 200,
                'message' => 'Created Succssfully!',
            ]);
        }
    }

    
    public function show($id)
    {
        $networth= CompetitorDetailsCompanyNetWorth::find($id);
        if ($networth)
            return response()->json([
                'status' => 200,
                'networth' => $networth
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

        $networth = CompetitorDetailsCompanyNetWorth::where([
            'compId' => $request->compId,
            'compNo' => $request->compNo,
            'accountYear'=> $request->accountYear
        ])
        ->where('id', '!=', $id)
        ->exists();
        if ($networth) {
            return response()->json([
                'status' => 404,
                'errors' => 'Branch Already Exists'
            ]);
        }
        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','accountYear'=>'required|string', 'accValue'=>'required|between:1.00,99999999999.99','edited_userid'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'errors' => $validator->messages(),
            ]);
        }


        $networth = CompetitorDetailsCompanyNetWorth::findOrFail($id)->update($request->all());
        if ($networth)
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
            $networth = CompetitorDetailsCompanyNetWorth::destroy($id);
            if($networth)    
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


    public function getNetWorthList($compid)
    {
        $networth = CompetitorDetailsCompanyNetWorth::where("compId",$compid)
        ->select('id', 'compNo', 'compId', 'accValue', 'accountYear',)
        ->orderBy('accountYear','DESC')
        ->get();

    if ($networth)
        return response()->json([
            'status' => 200,
            'networth' => $networth
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }
}
