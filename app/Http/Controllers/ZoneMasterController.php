<?php

namespace App\Http\Controllers;

use App\Models\ZoneMaster;
use App\Models\StateMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ZoneMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zonemaster=ZoneMaster::select('*')->get();
        return response()->json([
            'status' => 200,
            'zonemaster' => $zonemaster]);
    }

    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $zone = ZoneMaster::where('zone_name', '=', $request->zonename)->exists();
        if ($zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Zone Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        $zone = new ZoneMaster;
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();

        
        foreach($request->statelist as $row)
        {
            $zonehasstate = StateMaster::find($row['value']);
            $zonehasstate->zone_id= $zone->id;
            $zonehasstate->save();
        }
        

        if ($zone && $zonehasstate) {
            return response()->json([
                'status' => 200,
                'message' => 'Zone Added Succssfully!'
            ]);
        }
        }

    }

    
    public function show($id)
    {
        try{
        $zonemaster = DB::table('zone_masters as z')
        // ->join('zone_has_states as sub','z.id','sub.zone_id')
        ->join('state_masters as s','z.id','s.zone_id')
        ->select('z.id as zone_id','z.zone_name','z.active_status','s.id as state_id','s.state_name')
        ->where('z.id',$id)
        ->get();

        if($zonemaster){
            $zone['zone_id'] = $zonemaster[0]->zone_id;
            $zone['zone_name'] = $zonemaster[0]->zone_name;
            $zone['active_status'] = $zonemaster[0]->active_status;
            $i=0;
            foreach($zonemaster as $z)
            {
                $zone['statelist'][$i] = ['value' => $z->state_id, 'label' => $z->state_name];
                $i++;
            }

            return response()->json([
                'status' => 200,
                'zonename' => $zone
            ]);
        }
    }
    catch(\Exception $ex){
        return response()->json([
            'status' => 400,
            'message' => 'Unable to handle',
            'error' => $ex
        ]);
    }
    }

    
    public function edit(ZoneMaster $zoneMaster)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
       
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
            $zone_name = ZoneMaster::where('zone_name', '=', $request->zonename)
                                    ->where('id','!=',$id)                        
            ->exists();
            if ($zone_name) {
                return response()->json([
                    'status' => 400,
                    'errors' => 'Zone Name Already Exists'
                ]);
            }

        $zone = ZoneMaster::find($id);
        if (!$zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Somthing Went Wrong'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();
        $state = DB::table('state_masters')->where('country_id','105')->get();
            
            foreach($state as $zs) //state list which has zone id
            {  
                foreach($request->statelist as $row)  //input
                {  
                if($zs->id == $row['value'])
                {
                    if($zs->zone_id != $id)
                    {
                        $stateqry = StateMaster::findOrFail($zs->id);
                        $stateqry->zone_id=$id;
                        $stateqry->save();
                    }
                    break;
                }
                else if($zs->zone_id == $id)
                {
                    $isChecked=false;
                    foreach($request->statelist as $list)
                    {
                        if($zs->id == $list['value'])
                        {
                            $isChecked=true;
                        }
                    }
                    if(!$isChecked)
                    {
                    $stateqry = StateMaster::findOrFail($zs->id);
                    $stateqry->zone_id=null;
                    $stateqry->save();
                }  
                }              
            }
        }
        }

        if ($zone ) {
            return response()->json([
                'status' => 200,
                'message' => 'Zone Update Succssfully!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone=ZoneMaster::destroy($id);
        return response()->json([
            'status' => 200,
            'message' => 'Zone Has been Removed!'
        ]);
    }


    public function ZoneMasterTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {

        $header = ['Zone Name','Status'];
        $accessor = ['zone_name','active_status'];
        $zonemaster=ZoneMaster::select('*')->get();
        if ($zonemaster)
        return response()->json([
            'status' => 200,
            'title' => 'ZoneMaster',
            'header' => $header,
            'accessor' => $accessor,
            'data' => $zonemaster
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }  
   
    }
}
