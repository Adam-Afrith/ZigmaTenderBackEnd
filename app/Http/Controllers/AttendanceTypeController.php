<?php

namespace App\Http\Controllers;
use App\Models\Token;
use App\Models\AttendanceType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AttendanceTypeController extends Controller
{
    public function index()
    {
        $attendanceType= AttendanceType::get();
        if ($attendanceType) {
            return response()->json([
                'status' => 200,
                'list' => $attendanceType,
            ]);
        }
    }

      
    public function store(Request $request)
    {
          $user = Token::where("tokenid", $request->tokenId)->first();
          if($user['userid'])
          {
          $request->request->add(['created_by' => $user['userid']]);
          $request->request->remove('tokenId');
  
          // $existence = AttendanceType::where("compNo", $request->compNo)
          //     ->where("compId", $request->compId)
          //     ->where("strength", $request->strength)
          //     ->where("weakness", $request->weakness)
          //     ->select("*");
          
          
          // if ($existence) {
          //     return response()->json([
          //         'status' => 404,
          //         'message' => 'Already Exists!',
          //         "existence"=>$existence
          //     ]);
          // }
  
        //   $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','strength'=>'nullable|string','weakness'=>'nullable|string', 'created_by'=>'required|integer']);
        //   if ($validator->fails()) {
        //       return response()->json([
        //           'status' => 404,
        //           'message' => $validator->messages(),
        //       ]);
        //   }
        //   $attendanceType = AttendanceType::firstOrCreate($request->all());
        $attendanceType = new AttendanceType;
        $attendanceType->attendanceType = $request->attendanceType;
        $attendanceType->activeStatus = $request->activeStatus;
        $attendanceType->created_by = $user['userid'];
        $attendanceType->save();
          if ($attendanceType) {
              return response()->json([
                  'status' => 200,
                  'message' => 'Added Succssfully!',
              ]);
          }
        }
    }


    public function show($id)
    {
        $attendanceType= AttendanceType::find($id);
        if ($attendanceType)
            return response()->json([
                'status' => 200,
                'attendanceType' => $attendanceType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    
    public function update(Request $request, $id)
    {
        
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
        $request->request->add(['edited_by' => $user['userid']]);
        //Here is no need of token id when insert $request into table, so remove it form $request
        $request->request->remove('tokenId');

        $attendanceType = AttendanceType::where('attendanceType',$request->attendanceType)
        ->where('id', '!=', $id)
        ->exists();
        if ($attendanceType) {
            return response()->json([
                'status' => 404,
                'errors' => 'Attendance Type Already Exists'
            ]);
        }
        // $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','strength'=>'string','weakness'=>'string', 'edited_by'=>'required|integer']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 404,
        //         'errors' => $validator->messages(),
        //     ]);
        // }

    
        $attendanceType = AttendanceType::findOrFail($id);
        $attendanceType->attendanceType = $request->attendanceType;
        $attendanceType->activeStatus = $request->activeStatus;
        $attendanceType->edited_by = $user['userid'];
        $attendanceType->save();
    
        if ($attendanceType)
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
    }
    
    public function destroy($id)
    {
        try{
            $attendanceType = AttendanceType::destroy($id);
            if($attendanceType)    
            {
                return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!",
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!?',
                "errormessage" => "",
            ]);}
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    public function getAttendanceTypeList()
    {
        $attendancetype = AttendanceType::where("activeStatus", "=", "Active")->get();

        $attendancetypeList= [];
        foreach($attendancetype as $row){
            $attendancetypeList[] = ["value" => $row['id'], "label" =>  $row['attendanceType']] ;
        }
        
        return response()->json([
            'attendancetypelist' =>  $attendancetypeList,

        ]);
    }

    public function AttendanceTypeTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
        $header = ['Attendance Type','Status'];
        $accessor = ['attendanceType','activeStatus'];

        $attendanceType = AttendanceType::get();
            if ($attendanceType) {
                return response()->json([
                    'status' => 200,
                    'title' => 'AttendanceTypeMaster',
                    'header' => $header,
                    'accessor' => $accessor,
                    'data' => $attendanceType,
                ]);
            }
        }
    }
}
