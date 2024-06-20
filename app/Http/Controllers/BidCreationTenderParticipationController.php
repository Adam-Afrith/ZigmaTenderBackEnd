<?php

namespace App\Http\Controllers;

use App\Models\BidCreationTenderParticipation;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class BidCreationTenderParticipationController extends Controller
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
        //
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($user){
            $bidCreation = new BidCreationTenderParticipation;
            $bidCreation -> tenderparticipation = $request->bidcreationData['tenderParticipation']['value'];
            $bidCreation -> bidCreationMainId = $request->bidcreationData['bidcreationMainid'];
            $bidCreation -> createdby_userid = $userid ;
            $bidCreation -> updatedby_userid = 0 ;
            $bidCreation -> save();
        }

        if ($bidCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Saved!',
                'id' => $bidCreation['id'],
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
     * @param  \App\Models\BidCreationTenderParticipation  $bidCreationTenderParticipation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $BidCreationTenderParticipation = BidCreationTenderParticipation::where('bidCreationMainId',$id)->get();
        if (count($BidCreationTenderParticipation) > 0){
            return response()->json([
                'status' => 200,
                'BidCreationTenderParticipation' => $BidCreationTenderParticipation[0],
                'tenderpartcipation' => $BidCreationTenderParticipation[0]['tenderparticipation']
            ]);
        }

        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidCreationTenderParticipation  $bidCreationTenderParticipation
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreationTenderParticipation $bidCreationTenderParticipation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreationTenderParticipation  $bidCreationTenderParticipation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $updatedata = $request->bidcreationData;
            $updatedata['updatedby_userid']= $userid;
            $updatedata['tenderparticipation']= $request->bidcreationData['tenderParticipation']['value'];
           

            $bidcreationData = BidCreationTenderParticipation::findOrFail($id)->update($updatedata);
        }

        if ($bidcreationData)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidCreationTenderParticipation  $bidCreationTenderParticipation
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidCreationTenderParticipation $bidCreationTenderParticipation)
    {
        //
    }
}
