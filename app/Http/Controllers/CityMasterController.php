<?php

namespace App\Http\Controllers;

use App\Models\CityMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class CityMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // DB::enableQueryLog();
        $city = DB::table('city_masters')
        ->join('country_masters','country_masters.id','city_masters.country_id')
        ->join('state_masters','state_masters.id','city_masters.state_id')
        ->join('district_masters','district_masters.id','city_masters.district_id')
        ->where([
            'country_masters.country_status'=>'Active',
            'state_masters.state_status'=>'Active',
            'district_masters.district_status'=>'Active',
        ])
        ->select('country_masters.country_name','state_masters.state_name', 'district_masters.district_name', 'city_masters.city_name','city_masters.id','city_masters.city_status' ) 
        ->orderBy('country_masters.country_name', 'asc')
        ->orderBy('state_masters.state_name', 'asc')
        ->orderBy('district_masters.district_name', 'asc')
        ->orderBy('city_masters.city_name', 'asc')       
        ->get();

        // dd(DB::getQueryLog());
        // echo '<pre>';
        // print_r($city);
        // echo '</pre>';
        // die();
       
        if ($city)
            return response()->json([
                'status' => 200,
                'citylist' => $city
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
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
        $city = CityMaster::where('city_name', '=', $request->city_name)
        ->where('country_id', '=', $request->country_id)
        ->where('state_id', $request->state_id)
        ->where('district_id', $request->district_id)
        ->exists();
        if ($city) {
            return response()->json([
                'status' => 400,
                'errors' => ucfirst(strtolower($request->city_name)) .' Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['city_name' => 'required|string', 'city_status' => 'required', 'country_id'=>'required|integer','state_id'=>'required|integer', 'district_id'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $city = CityMaster::firstOrCreate($request->all());
        if ($city) {
            return response()->json([
                'status' => 200,
                'message' => ucfirst(strtolower($request->city_name)) .' Created Successfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CityMaster  $cityMaster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $city = CityMaster::find($id);
      
        if ($city)
            return response()->json([
                'status' => 200,
                'city' => $city
            ]);
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
     * @param  \App\Models\CityMaster  $cityMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(CityMaster $cityMaster)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CityMaster  $cityMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $city = CityMaster::where([
            'city_name' => $request->city_name,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'country_id' => $request->country_id,
        ])
        ->where('id', '!=', $id)
        ->exists();
        if ($city) {
            return response()->json([
                'status' => 400,
                'errors' => 'City Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['city_name' => 'required|string', 'city_status' => 'required', 'country_id'=>'required|integer','state_id'=>'required|integer', 'district_id'=>'required|integer']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }


        $city = CityMaster::findOrFail($id)->update($request->all());
        if ($city)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CityMaster  $cityMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $city = CityMaster::destroy($id);
            if ($city) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The Provided Credentials are Incorrect.',
                "errormessage" => '',
            ]);
        }
    }
    catch(\Illuminate\Database\QueryException $ex){
        $error = $ex->getMessage(); 
            
        return response()->json([
            'status' => 404,
            'message' => 'Unable to delete! This data is used in another file/form/table.',
            "errormessage" => $error,
        ]);
    }
    }

    public function getCityList($countryId, $stateid, $districtid, $savedcity){
        DB::enableQueryLog(); 

        $cities = CityMaster::where("country_id",$countryId)
        ->where("state_id",$stateid)
        ->where("district_id",$districtid)
        ->whereIn("country_id",function($query){
            $query->select('id')
            ->from('country_masters')
            ->where('country_status',"Active");
        })
        ->whereIn("state_id",function($query){
            $query->select('id')
            ->from('state_masters')
            ->where('state_status',"Active");
        })
        ->whereIn("district_id",function($query){
            $query->select('id')
            ->from('district_masters')
            ->where('district_status',"Active");
        })
        ->where("city_status", "=", "Active")
        ->orWhere('id',$savedcity)
        ->orWhere("id", function($query) use ($countryId, $stateid, $districtid, $savedcity){
            $query->select('id')
            ->from('city_masters')
            ->where('id',$savedcity)
            ->where('country_id',$countryId)
            ->where('state_id',$stateid)
            ->where('district_id',$districtid);
        })
        ->get();
        
        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);
       
        
        $cityList = array();
        foreach($cities as $city){
            $cityList[] = ["value" => $city['id'], "label" =>  $city['city_name']] ;
        }


        return  response()->json([
            'cityList' =>  $cityList,
            'sqlquery' => $query
        ]);
    }

    public function CityMasterTable(Request $request){
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $accessor =[];
        if($userid)
        {
            $tableName = 'city_masters';
            $header=['Country','State','District','City','Status'];
            $city = DB::table('city_masters')
            ->join('country_masters','country_masters.id','city_masters.country_id')
            ->join('state_masters','state_masters.id','city_masters.state_id')
            ->join('district_masters','district_masters.id','city_masters.district_id')
            ->where([
                'country_masters.country_status'=>'Active',
                'state_masters.state_status'=>'Active',
                'district_masters.district_status'=>'Active',
            ])
            ->select('country_masters.country_name','state_masters.state_name', 'district_masters.district_name', 'city_masters.city_name','city_masters.id','city_masters.city_status' ) 
            ->orderBy('country_masters.country_name', 'asc')
            ->orderBy('state_masters.state_name', 'asc')
            ->orderBy('district_masters.district_name', 'asc')
            ->orderBy('city_masters.city_name', 'asc')       
            ->get();

           foreach($city[0] as $key => $value ){

            if($key === 'id'){
                continue;
            }
                $accessor[]=$key;
           }
           
            if ($city)
                return response()->json([
                    'data' => $city,
                    'header'=> $header,
                    'title'=>'District Master',
                    'accessor'=>  $accessor,
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
