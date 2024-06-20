<?php

namespace App\Http\Controllers;

use App\Models\ULBDetails;
use Illuminate\Http\Request;
use App\Models\Token;

class ULBDetailsController extends Controller
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

     
       

        if($userid){
            $CustomerCreation = new ULBDetails;
            $CustomerCreation ->area = $request->ulbdetails['area'];
            $CustomerCreation ->population2011 = $request->ulbdetails['population2011'];
            // $CustomerCreation ->presentpopulation = $request->ulbdetails['presentpopulation'];
            $CustomerCreation ->wards = $request->ulbdetails['wards'];
            $CustomerCreation ->households = $request->ulbdetails['households'];
            $CustomerCreation ->commercial = $request->ulbdetails['commercial'];
            $CustomerCreation ->ABbusstand = $request->ulbdetails['ABbusstand'];
            $CustomerCreation ->CDbusstand = $request->ulbdetails['CDbusstand'];
            $CustomerCreation ->market_morethan_oneacre = $request->ulbdetails['market_morethan_oneacre'];
            $CustomerCreation ->market_lessthan_oneacre = $request->ulbdetails['market_lessthan_oneacre'];
            $CustomerCreation ->lengthofroad = $request->ulbdetails['lengthofroad'];
            $CustomerCreation ->lengthofrouteroad = $request->ulbdetails['lengthofrouteroad'];
            $CustomerCreation ->lengthofotherroad = $request->ulbdetails['lengthofotherroad'];
            $CustomerCreation ->lengthoflanes = $request->ulbdetails['lengthoflanes'];
            $CustomerCreation ->lengthofpucca = $request->ulbdetails['lengthofpucca'];
            $CustomerCreation ->lengthofcutcha = $request->ulbdetails['lengthofcutcha'];
            $CustomerCreation ->parks = $request->ulbdetails['parks'];
            $CustomerCreation ->parksforpublicuse = $request->ulbdetails['parksforpublicuse'];
            $CustomerCreation ->tricycle = $request->ulbdetails['tricycle'];
            $CustomerCreation ->bov = $request->ulbdetails['bov'];
            $CustomerCreation ->bovrepair = $request->ulbdetails['bovrepair'];
            $CustomerCreation ->lcv = $request->ulbdetails['lcv'];
            $CustomerCreation ->lcvrepair = $request->ulbdetails['lcvrepair'];
            $CustomerCreation ->compactor = $request->ulbdetails['compactor'];
            $CustomerCreation ->hookloaderwithcapacity = $request->ulbdetails['hookloaderwithcapacity'];
            $CustomerCreation ->compactorbin = $request->ulbdetails['compactorbin'];
            $CustomerCreation ->hookloader = $request->ulbdetails['hookloader'];
            $CustomerCreation ->tractortipper = $request->ulbdetails['tractortipper'];
            $CustomerCreation ->lorries = $request->ulbdetails['lorries'];
            $CustomerCreation ->jcb = $request->ulbdetails['jcb'];
            $CustomerCreation ->bobcat = $request->ulbdetails['bobcat'];
            $CustomerCreation ->sanitaryworkers_sanctioned = $request->ulbdetails['sanitaryworkers_sanctioned'];
            $CustomerCreation ->sanitaryworkers_inservice = $request->ulbdetails['sanitaryworkers_inservice'];
            $CustomerCreation ->sanitarysupervisor_sanctioned = $request->ulbdetails['sanitarysupervisor_sanctioned'];
            $CustomerCreation ->sanitarysupervisor_inservice = $request->ulbdetails['sanitarysupervisor_inservice'];
            $CustomerCreation ->permanentdrivers = $request->ulbdetails['permanentdrivers'];
            $CustomerCreation ->regulardrivers = $request->ulbdetails['regulardrivers'];
            $CustomerCreation ->publicgathering = $request->ulbdetails['publicgathering'];
            $CustomerCreation ->secondarystorage = $request->ulbdetails['secondarystorage'];
            $CustomerCreation ->transferstation = $request->ulbdetails['transferstation'];
            $CustomerCreation ->households_animatorsurvey = $request->ulbdetails['households_animatorsurvey'];
            $CustomerCreation ->assessments_residential = $request->ulbdetails['assessments_residential'];
            $CustomerCreation ->assessments_commercial = $request->ulbdetails['assessments_commercial'];
            $CustomerCreation ->cust_creation_mainid = $request->ulbdetails['cust_creation_mainid'];
            $CustomerCreation ->createdby_userid = $userid;
            $CustomerCreation ->updatedby_userid = 0;
            $CustomerCreation -> save();
        }

        if ($CustomerCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'ULB Details Saved!',
                'id' => $CustomerCreation['id'],
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
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $UlbDetails = ULBDetails::where('cust_creation_mainid', $id)->get()->first();
        if ($UlbDetails) {
            return response()->json([
                'status' => 200,
                'ulbdetails' => $UlbDetails,
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'No data'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(ULBDetails $uLBDetails)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if(!$userid){
            return response()->json([
                'status' => 400,
                'message' => "Unable to update!"
            ]);
        }

        $ULBDetails = ULBDetails::findOrFail($id)->update([
            'area' => $request->ulbdetails['area'],
            'population2011' => $request->ulbdetails['population2011'],
            // 'presentpopulation' => $request->ulbdetails['presentpopulation'],
            'wards' => $request->ulbdetails['wards'],
            'households' => $request->ulbdetails['households'],
            'commercial' => $request->ulbdetails['commercial'],
            'ABbusstand' => $request->ulbdetails['ABbusstand'],
            'CDbusstand' => $request->ulbdetails['CDbusstand'],
            'market_morethan_oneacre' => $request->ulbdetails['market_morethan_oneacre'],
            'market_lessthan_oneacre' => $request->ulbdetails['market_lessthan_oneacre'],
            'lengthofroad' => $request->ulbdetails['lengthofroad'],
            'lengthofrouteroad' => $request->ulbdetails['lengthofrouteroad'],
            'lengthofotherroad' => $request->ulbdetails['lengthofotherroad'],
            'lengthoflanes' => $request->ulbdetails['lengthoflanes'],
            'lengthofpucca' => $request->ulbdetails['lengthofpucca'],
            'lengthofcutcha' => $request->ulbdetails['lengthofcutcha'],
            'parks' => $request->ulbdetails['parks'],
            'parksforpublicuse' => $request->ulbdetails['parksforpublicuse'],
            'tricycle' => $request->ulbdetails['tricycle'],
            'bov' => $request->ulbdetails['bov'],
            'bovrepair' => $request->ulbdetails['bovrepair'],
            'lcv' => $request->ulbdetails['lcv'],
            'lcvrepair' => $request->ulbdetails['lcvrepair'],
            'compactor' => $request->ulbdetails['compactor'],
            'hookloaderwithcapacity' => $request->ulbdetails['hookloaderwithcapacity'],
            'compactorbin' => $request->ulbdetails['compactorbin'],
            'hookloader' => $request->ulbdetails['hookloader'],
            'tractortipper' => $request->ulbdetails['tractortipper'],
            'lorries' => $request->ulbdetails['lorries'],
            'jcb' => $request->ulbdetails['jcb'],
            'bobcat' => $request->ulbdetails['bobcat'],
            'sanitaryworkers_sanctioned' => $request->ulbdetails['sanitaryworkers_sanctioned'],
            'sanitaryworkers_inservice' => $request->ulbdetails['sanitaryworkers_inservice'],
            'sanitarysupervisor_sanctioned' => $request->ulbdetails['sanitarysupervisor_sanctioned'],
            'sanitarysupervisor_inservice' => $request->ulbdetails['sanitarysupervisor_inservice'],
            'permanentdrivers' => $request->ulbdetails['permanentdrivers'],
            'regulardrivers' => $request->ulbdetails['regulardrivers'],
            'publicgathering' => $request->ulbdetails['publicgathering'],
            'secondarystorage' => $request->ulbdetails['secondarystorage'],
            'transferstation' => $request->ulbdetails['transferstation'],
            'households_animatorsurvey' => $request->ulbdetails['households_animatorsurvey'],
            'assessments_residential' => $request->ulbdetails['assessments_residential'],
            'assessments_commercial' => $request->ulbdetails['assessments_commercial'],
            'cust_creation_mainid' => $request->ulbdetails['cust_creation_mainid'],
            'updatedby_userid'=>$userid,
        ]);

        if ($ULBDetails)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(ULBDetails $uLBDetails)
    {
        //
    }
}
