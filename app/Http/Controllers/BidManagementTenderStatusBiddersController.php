<?php

namespace App\Http\Controllers;

use App\Models\BidManagementTenderStatusBidders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;

class BidManagementTenderStatusBiddersController extends Controller
{
    public function store(Request $request)
    {
         $user = Token::where("tokenid", $request->tokenId)->first();
         $request->request->add(['created_userid' => $user['userid']]);
         $request->request->remove('tokenId');
         $validator = Validator::make($request->all(), ['bidid' => 'required|integer','bidders' => 'required|integer','created_userid'=>'required|integer']);
         if ($validator->fails()) {
             return response()->json([
                 'status' => 404,
                 // 'message' =>"Not able to Add Strength/Weakness details now..!",
                 'message' => $validator->messages(),
             ]);
         }

         $bidders =new BidManagementTenderStatusBidders;
         $bidders->bidid=$request->bidid;
         $bidders->no_of_bidders=$request->bidders;
         $bidders->created_userid=$request->created_userid;
         $bidders->save();
         //$bidders = BidManagementTenderStatusBidders::firstOrCreate($request->all());
         if ($bidders) {
             return response()->json([
                 'status' => 200,
                 'message' => 'Added Succssfully!',
             ]);
         }
    }

    
    public function show($id)
    {
        $bidders=BidManagementTenderStatusBidders::where("bidid",$id)->get()->first();
        if($bidders){
            return response()->json([
                'status' => 200,
                'bidders' => $bidders->no_of_bidders
            ]);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
   

    public function edit($id)
    {
        return "Edit Func -".$id;
    }
    
    
    public function update(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();    
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');
        $request->request->add(['no_of_bidders' => $request->bidders]);
        $request->request->remove('bidders');

        $validator = Validator::make($request->all(), ['bidid' => 'required|integer','no_of_bidders' => 'required|integer','edited_userid'=>'required|integer']);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'errors' => $validator->messages(),
            ]);
        }

    
        $bidders = BidManagementTenderStatusBidders::where("bidid",$id)->update($request->all());
        if ($bidders)
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


    public function getBidders($id){
        
        $bidders=BidManagementTenderStatusBidders::where("bidid",$id)
        ->select("*")
        ->get()->first();
        if($bidders){
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();    
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');
        
        if($user['userid'])
        {
            $bidders = BidManagementTenderStatusBidders::where("bidid",$id)->update($request->all());
            if ($bidders)
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
        else{
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }

    
       
    }

    
}
