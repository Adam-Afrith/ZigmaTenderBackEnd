<?php
namespace App\Http\Controllers;
use App\Models\UnitMaster;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitMasterController extends Controller
{
    public function index()
    {
        
        $unit = UnitMaster::orderBy('created_at', 'desc')->get();

        if ($unit)
            return response()->json([
                'status' => 200,
                'unit' => $unit
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
        $unitRes = UnitMaster::where('unit_name', '=', $request->unit_name)->exists();
        if ($unitRes) {
            return response()->json([
                'status' => 400,
                'message' => 'Unit Name Already Exists!'
            ]);
        }


        $validator = Validator::make($request->all(), ['unit_name' => 'required|string', 'unit_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->messages(),
            ]);
        }

        $unitObj = UnitMaster::firstOrCreate($request->all());
        if ($unitObj) {
            return response()->json([
                'status' => 200,
                'message' => 'Unit Created Succssfully!'
            ]);
        }
    }


    public function show($id)
    {
        $unit = UnitMaster::find($id);
        if ($unit)
            return response()->json([
                'status' => 200,
                'unit' => $unit
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

        $unitRes = UnitMaster::where('unit_name', '=', $request->unit_name)
            ->where('unit_status', '=', $request->unit_status)
            ->where('id', '!=', $id)->exists();
        if ($unitRes) {
            return response()->json([
                'status' => 400,
                'message' => 'Unit Name Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['unit_name' => 'required|string', 'unit_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->message(),
            ]);
        } 
        
        $unit = UnitMaster::findOrFail($id)->update($request->all());

            if ($unit)
                return response()->json([
                    'status' => 200,
                    'message' => "Updated Successfully!",
                ]);
            else{
                return response()->json([
                    'status' => 400,
                    'message' => "Sorry, Failed to Update, Try again later"
                ]);
            }
        }



    public function destroy($id)
    {
        $unit = UnitMaster::destroy($id);
        if ($unit)
            return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);

        else {
            return response()->json([
                'status' => 400,
                'message' => 'The Provided Credentials are Incorrect.'
            ]);
        }
    }


    public function getunitList()
    {
        
            $units = UnitMaster::where("unit_status", "=", "Active")
            ->get();

            
            $unitList = array();
            foreach($units as $unit){
                $unitList[] = ["value" => $unit['id'], "label" =>  $unit['unit_name']] ;
            }
            return  response()->json([
                'unitList' =>  $unitList
            ]);
        
    }

    public function getListofUnits()
    {
        

        $units = UnitMaster::where("unit_status", "=", "Active")
        ->get();

        
        $unitList = array();
        foreach($units as $unit){
            $unitList[] = ["value" => $unit['id'], "label" =>  $unit['unit_name']] ;
        }
        return  response()->json([
            'unitList' =>  $unitList
        ]);
    
    }

    public function UnitMasterTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {

        $header = ['Unit Name','Status'];
        // $accessor = [
        //     [
        //         'unit_name' => 'UnitName',
        //         'unit_status' => 'UnitStatus',
        //         'action' => 'action'
        //     ]
        // ];
        $accessor = ['unit_name','unit_status']; 
 

        $unit = UnitMaster::orderBy('created_at', 'desc')->get();

        if ($unit)
            return response()->json([
                'status' => 200,
                'title' => 'UnitMaster',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $unit,
                
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
