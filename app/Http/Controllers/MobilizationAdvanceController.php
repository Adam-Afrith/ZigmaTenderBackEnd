<?php

namespace App\Http\Controllers;

use App\Models\MobilizationAdvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class MobilizationAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data= $request->mobilizationData;   
        $validator = Validator::make($data, [
            'mobAdvance' => 'required|integer',
            'bankName' => 'required|string',
            'bankBranch' => 'required|string',
            'mobAdvMode' => 'required|string',
            'dateMobAdv' => 'required|date',
            'validUpto' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' =>"Not able to Add Strength/Weakness details now..!",
                'error' => $validator->messages(),
            ]);
        }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
    if($userid)
    {
        $MobilizationAdvance = new MobilizationAdvance;
        $MobilizationAdvance -> bidid = $request->bidid;
        $MobilizationAdvance -> mobAdvance = $request->mobilizationData['mobAdvance'];
        $MobilizationAdvance -> bankName = $request->mobilizationData['bankName'];
        $MobilizationAdvance -> bankBranch = $request->mobilizationData['bankBranch'];
        $MobilizationAdvance -> mobAdvMode = $request->mobilizationData['mobAdvMode'];
        $MobilizationAdvance -> dateMobAdv = $request->mobilizationData['dateMobAdv'];
        $MobilizationAdvance -> validUpto = $request->mobilizationData['validUpto'];
        $MobilizationAdvance -> createdby_userid = $userid ;
        $MobilizationAdvance -> updatedby_userid = 0 ;
        $MobilizationAdvance -> save();
    }
        if ($MobilizationAdvance) {
            return response()->json([
                'status' => 200,
                'message' => 'Mobilzation Advance Has created Succssfully!',
                'Mobilization' => $MobilizationAdvance,
                'bidid' => $MobilizationAdvance['bidid'],
                'id' => $MobilizationAdvance['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MobilizationAdvance  $mobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $MobilizationAdvance = MobilizationAdvance::where('bidid','=',$id)->get();
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
     * @param  \App\Models\MobilizationAdvance  $mobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function edit(MobilizationAdvance $mobilizationAdvance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MobilizationAdvance  $mobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data= $request->mobilizationData;
        $validator = Validator::make($data, [
            'mobAdvance' => 'required|integer',
            'bankName' => 'required|string',
            'bankBranch' => 'required|string',
            'mobAdvMode' => 'required|string',
            'dateMobAdv' => 'required|date',
            'validUpto' => 'required|date'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' =>"Not able to Add Strength/Weakness details now..!",
                'errors' => $validator->messages(),
            ]);
        }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
        if($userid)
        {
            $MobilizationAdvance = MobilizationAdvance::findOrFail($id)->update([
                'mobAdvance' => $request->mobilizationData['mobAdvance'],
                'bankName' => $request->mobilizationData['bankName'],
                'bankBranch' => $request->mobilizationData['bankBranch'],
                'mobAdvMode' => $request->mobilizationData['mobAdvMode'],
                'dateMobAdv' => $request->mobilizationData['dateMobAdv'],
                'validUpto' => $request->mobilizationData['validUpto'],
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
     * @param  \App\Models\MobilizationAdvance  $mobilizationAdvance
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobilizationAdvance $mobilizationAdvance)
    {
        //
    }
    public function getMobList($mobId){
        $Mobilization = MobilizationAdvance::where('id','=',$mobId)->get();
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
