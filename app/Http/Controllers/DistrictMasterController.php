<?php

namespace App\Http\Controllers;

use App\Models\DistrictMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class DistrictMasterController extends Controller
{
    public function index()
    {
        $district = DistrictMaster::join("state_masters",'district_masters.state_id','state_masters.id')
                    ->join("country_masters",'country_masters.id','district_masters.country_id')
                    ->where([
                        'country_masters.country_status'=>'Active',
                        'state_masters.state_status'=>'Active',
                    ])
                    ->select('country_masters.country_name','state_masters.state_name', 'district_masters.district_name','district_masters.id','district_masters.district_status') 
                    ->orderBy('country_masters.country_name', 'asc')
                    ->orderBy('state_masters.state_name', 'asc')
                    ->orderBy('district_masters.district_name', 'asc')
                    ->get();


        if ($district)
            return response()->json([
                'status' => 200,
                'district' => $district
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function create()
    {
        return "Create Function";
    }


    public function store(Request $request)
    {
        $districtRes = DistrictMaster::where('district_name', $request->district_name)->exists();
        if ($districtRes) {
            return response()->json([
                'status' => 400,
                'message' => 'District Name Already Exists!'
            ]);
        }


        $validator = Validator::make($request->all(), ['district_name' => 'required|string','country_id'=>'required|integer','state_id'=>'required|integer', 'district_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->messages(),
            ]);
        }

        $districtObj = DistrictMaster::firstOrCreate($request->all());
        if ($districtObj) {
            return response()->json([
                'status' => 200,
                'message' => 'District Created Succssfully!'
            ]);
        }
    }


    public function show($id)
    {
        $district = DistrictMaster::find($id);
        if ($district)
            return response()->json([
                'status' => 200,
                'district' => $district
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }


    public function edit($id)
    {
        return "Edit Function";
    }


    public function update(Request $request, $id)
    {

        $districtRes = DistrictMaster::where('district_name', '=', $request->district_name)
            ->where('district_status', '=', $request->district_status)
            ->where('id', '!=', $id)->exists();
        if ($districtRes) {
            return response()->json([
                'status' => 400,
                'message' => 'District Name Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['district_name' => 'required|string', 'district_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->message(),
            ]);
        }

        $district = DistrictMaster::findOrFail($id)->update($request->all());

        if ($district)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!",
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => "Sorry, Failed to Update, Try again later"
            ]);
        }
    }



    public function destroy($id)
    {
        try{
            $district = DistrictMaster::destroy($id);
            if ($district) {
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

    public function getDistrictList($countryId, $stateid){
        $districts = DistrictMaster::where("country_id",$countryId)
        ->where("state_id",$stateid)
        ->where("district_status", "=", "Active")
        ->get();
        //dd($districts);
        $districtList = array();
        foreach($districts as $district){
            $districtList[] = ["value" => $district['id'], "label" =>  $district['district_name']] ;
        }

        return  response()->json([
            'districtList' =>  $districtList
        ]);
    }

    public function getDistrictListofstate($countryId, $stateid, $saveddistrict){
        $districts = DistrictMaster::where("country_id",$countryId)
        ->where("state_id",$stateid)
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
        ->where("district_status", "=", "Active")
        ->orWhere("id", function($query) use ($countryId, $stateid, $saveddistrict){
            $query->select('id')
            ->from('district_masters')
            ->where('id',$saveddistrict)
            ->where('country_id',$countryId)
            ->where('state_id',$stateid);
        })
        ->get();
        //dd($districts);
        $districtList = array();
        foreach($districts as $district){
            $districtList[] = ["value" => $district['id'], "label" =>  $district['district_name']] ;
        }

        return  response()->json([
            'districtList' =>  $districtList
        ]);
    }

    public function DistrictMasterTable(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
    
        if($userid)
        {
            $tableName = 'district_masters';
            $header=['COUNTRY','STATE','DISTRICT NAME','STATUS'];
            $specificColumns = ['country_id','state_id','district_name','district_status'];
            $columnNames = DB::select("SHOW COLUMNS FROM $tableName");
            $filteredColumns = array_intersect($specificColumns, array_column($columnNames, 'Field'));
            $district = DistrictMaster::join("state_masters",'district_masters.state_id','state_masters.id')
            ->join("country_masters",'country_masters.id','district_masters.country_id')
            ->where([
                'country_masters.country_status'=>'Active',
                'state_masters.state_status'=>'Active',
            ])
            ->select('country_masters.country_name','state_masters.state_name', 'district_masters.district_name','district_masters.id','district_masters.district_status') 
            ->orderBy('country_masters.country_name', 'asc')
            ->orderBy('state_masters.state_name', 'asc')
            ->orderBy('district_masters.district_name', 'asc')
            ->get();

            $modifiedAccessor = array_map(function ($value)
             {
                if ($value === "country_id")
                 {
                    return "country_name";
                } elseif ($value === "state_id") 
                {
                    return "state_name";
                }
                return $value;
            }, $filteredColumns);
                
            return response()->json([
                'data'=>$district,
                'header'=>$header,
                'title'=>'District Master',
                'accessor'=> $modifiedAccessor,
               
                
            ]);
        }
    }
    
}
