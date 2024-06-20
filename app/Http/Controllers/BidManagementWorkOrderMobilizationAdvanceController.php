<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderMobilizationAdvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class BidManagementWorkOrderMobilizationAdvanceController extends Controller
{
  
    public function index()
    {
        //
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        
        $data= $request->mobilizationData;   
        // $validator = Validator::make($data, [
        //     'mobAdvance' => 'required|integer',
        //     'bankName' => 'required|string',
        //     'bankBranch' => 'required|string',
        //     'mobAdvMode' => 'required|string',
        //     'dateMobAdv' => 'required|date',
        //     'validUpto' => 'required|date'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' =>"Not able to Add Strength/Weakness details now..!",
        //         'message' => $validator->messages(),
        //     ]);
        // }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
    if($userid)
    {
        $MobilizationAdvance = new BidManagementWorkOrderMobilizationAdvance;
        $MobilizationAdvance -> bidid = $request->bidid;
        $MobilizationAdvance -> mobadvance = $request->mobilizationData['mobAdvance'];
        $MobilizationAdvance -> bankname = $request->mobilizationData['bankName'];
        $MobilizationAdvance -> bankbranch = $request->mobilizationData['bankBranch'];
        $MobilizationAdvance -> mobadvmode = $request->mobilizationData['mobAdvMode'];
        $MobilizationAdvance -> datemobadv = $request->mobilizationData['dateMobAdv'];
        $MobilizationAdvance -> validupto = $request->mobilizationData['validUpto'];
        $MobilizationAdvance -> createdby_userid = $userid ;
        $MobilizationAdvance -> updatedby_userid = 0 ;
        $MobilizationAdvance -> save();
    }
        if ($MobilizationAdvance) 
        {
            return response()->json([
                'status' => 200,
                'message' => 'Mobilzation Advance Has created Succssfully!',
                'Mobilization' => $MobilizationAdvance,
                // 'bidid' => $MobilizationAdvance['bidid'],
                // 'id' => $MobilizationAdvance['id'],
            ]);
        }
        else
        {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderMobilizationAdvance  $bidManagementWorkOrderMobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $MobilizationAdvance = BidManagementWorkOrderMobilizationAdvance::where('bidid','=',$id)->get();
        if ($MobilizationAdvance){
            return response()->json([
                'status' => 200,
                'MobilizationAdvance' => $MobilizationAdvance,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderMobilizationAdvance  $bidManagementWorkOrderMobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderMobilizationAdvance $bidManagementWorkOrderMobilizationAdvance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderMobilizationAdvance  $bidManagementWorkOrderMobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data= $request->mobilizationData;
        // $validator = Validator::make($data, [
        //     'mobAdvance' => 'required|integer',
        //     'bankName' => 'required|string',
        //     'bankBranch' => 'required|string',
        //     'mobAdvMode' => 'required|string',
        //     'dateMobAdv' => 'required|date',
        //     'validUpto' => 'required|date'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' =>"Not able to Add Strength/Weakness details now..!",
        //         'errors' => $validator->messages(),
        //     ]);
        // }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
        if($userid)
        {
            $MobilizationAdvance = BidManagementWorkOrderMobilizationAdvance::findOrFail($id)->update([
                'mobadvance' => $request->mobilizationData['mobAdvance'],
                'bankname' => $request->mobilizationData['bankName'],
                'bankbranch' => $request->mobilizationData['bankBranch'],
                'mobadvmode' => $request->mobilizationData['mobAdvMode'],
                'datemobadv' => $request->mobilizationData['dateMobAdv'],
                'validupto' => $request->mobilizationData['validUpto'],
                'updatedby_userid'=>  $userid 
            ]);
        }
            if ($MobilizationAdvance){
                return response()->json([
                    'status' => 200,
                    'MobilizationAdvance' => $MobilizationAdvance
                ]);
            }
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderMobilizationAdvance  $bidManagementWorkOrderMobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidManagementWorkOrderMobilizationAdvance $bidManagementWorkOrderMobilizationAdvance)
    {
        //
    }
    public function getMobList($mobId){
        $Mobilization = BidManagementWorkOrderMobilizationAdvance::where('id','=',$mobId)->get();
        if ($Mobilization){
            return response()->json([
                'status' => 200,
                'Mobilization' => $Mobilization,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
