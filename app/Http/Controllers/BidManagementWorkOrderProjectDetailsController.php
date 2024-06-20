<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderProjectDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class BidManagementWorkOrderProjectDetailsController extends Controller
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

        $data = $request->projectDetails;

        // $validator = Validator::make($data,[
        //     'ProPeriod'=>'required|string',
        //     'mobPeriod'=>'required|string',
        //     'monsoonPeriod'=>'required|string',
        //     'monthDuration'=>'required|string',
        //     'supplyScape'=>'required|string',
        //     'supplyDate'=>'required|date',
        //     'erectionStart'=>'required|date',
        //     'commercialProduc'=>'required|date',
        //     'tarCompletion'=>'required|date',
        //     'producCompletion'=>'required|date'
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'unable to save',
        //         'errors' => $validator->messages(),
        //     ]);
        // }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
        
        if($userid){
            $Projectdetails = new BidManagementWorkOrderProjectDetails;
            $Projectdetails -> bidid = $request->bidid;
            $Projectdetails -> properiod = $request->projectDetails['ProPeriod'];
            $Projectdetails -> mobperiod = $request->projectDetails['mobPeriod'];
            $Projectdetails -> monsoonperiod = $request->projectDetails['monsoonPeriod'];
            $Projectdetails -> monthduration = $request->projectDetails['monthDuration'];
            $Projectdetails -> supplyscape = $request->projectDetails['supplyScape'];
            $Projectdetails -> supplydate = $request->projectDetails['supplyDate'];
            $Projectdetails -> erectionstart = $request->projectDetails['erectionStart'];
            $Projectdetails -> commercialproduc = $request->projectDetails['commercialProduc'];
            $Projectdetails -> tarcompletion = $request->projectDetails['tarCompletion'];
            $Projectdetails -> produccompletion = $request->projectDetails['producCompletion'];
            $Projectdetails -> createdby_userid = $userid ;
            $Projectdetails -> updatedby_userid = 0 ;
            $Projectdetails -> save();
        }

        if ($Projectdetails) {
            return response()->json([
                'status' => 200,
                'message' => 'Project Details Has created Succssfully!',
                'Projectdetails' => $Projectdetails,
                // 'bidid' => $Projectdetails['bidid'],
                // 'id' => $Projectdetails['id'],
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
     * @param  \App\Models\BidManagementWorkOrderProjectDetails  $bidManagementWorkOrderProjectDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Projectdetails = BidManagementWorkOrderProjectDetails::where('bidid','=',$id)->get();
        if ($Projectdetails){
            return response()->json([
                'status' => 200,
                'Projectdetails' => $Projectdetails,
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
     * @param  \App\Models\BidManagementWorkOrderProjectDetails  $bidManagementWorkOrderProjectDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderProjectDetails $bidManagementWorkOrderProjectDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderProjectDetails  $bidManagementWorkOrderProjectDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = $request->projectDetails;

        // $validator = Validator::make($data,[
        //     'ProPeriod'=>'required|string',
        //     'mobPeriod'=>'required|string',
        //     'monsoonPeriod'=>'required|string',
        //     'monthDuration'=>'required|string',
        //     'supplyScape'=>'required|string',
        //     'supplyDate'=>'required|date',
        //     'erectionStart'=>'required|date',
        //     'commercialProduc'=>'required|date',
        //     'tarCompletion'=>'required|date',
        //     'producCompletion'=>'required|date'
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "unable to save",
        //         'errors' => $validator->messages(),
        //     ]);
        // }
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');

        if($userid){
            $projectDetails = BidManagementWorkOrderProjectDetails::findOrFail($id)->update([
                'properiod' => $request->projectDetails['ProPeriod'],
                'mobperiod' => $request->projectDetails['mobPeriod'],
                'monsoonperiod' => $request->projectDetails['monsoonPeriod'],
                'monthduration' => $request->projectDetails['monthDuration'],
                'supplyscape' => $request->projectDetails['supplyScape'],
                'supplydate' => $request->projectDetails['supplyDate'],
                'erectionstart' => $request->projectDetails['erectionStart'],
                'commercialproduc' => $request->projectDetails['commercialProduc'],
                'tarcompletion' => $request->projectDetails['tarCompletion'],
                'produccompletion' => $request->projectDetails['producCompletion'],
                'updatedby_userid'=>  $userid 
            ]);
        }

        if ($projectDetails){
            return response()->json([
                'status' => 200,
                'projectDetails' => $projectDetails
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
     * @param  \App\Models\BidManagementWorkOrderProjectDetails  $bidManagementWorkOrderProjectDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidManagementWorkOrderProjectDetails $bidManagementWorkOrderProjectDetails)
    {
        //
    }

    public function getProList($proid){
        $Projectdetails = BidManagementWorkOrderProjectDetails::where('id','=',$proid)->get();
        if ($Projectdetails){
            return response()->json([
                'status' => 200,
                'Projectdetails' => $Projectdetails,
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
