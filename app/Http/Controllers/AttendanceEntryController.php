<?php

namespace App\Http\Controllers;
use App\Models\Token;
use App\Models\AttendanceEntry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AttendanceEntryController extends Controller
{
    public function index()
    {
        $attendance= DB::table('attendance_entries as ae')
        ->join('users as u','ae.userId','u.id')
        ->join('attendance_types as a','ae.attendanceType','a.id')
        ->select('u.userName','a.attendanceType as attendanceTypeName','ae.*')
        ->get();
        if ($attendance) {
            return response()->json([
                'status' => 200,
                'list' => $attendance,
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
  
          // $existence = CompetitorDetailsProsCons::where("compNo", $request->compNo)
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
          $attendance = AttendanceEntry::firstOrCreate($request->all());
          if ($attendance) {
              return response()->json([
                  'status' => 200,
                  'message' => 'Added Succssfully!',
              ]);
          }
        }
    }


    public function show($id)
    {
        $attendance= AttendanceEntry::find($id);
        if ($attendance)
            return response()->json([
                'status' => 200,
                'attendance' => $attendance
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

        // $attendance = CompetitorDetailsProsCons::where('compId',$request->compId)
        // ->where('compNo', $request->compNo)
        // ->where('strength',$request->strength)
        // ->orWhere('weakness',$request->weakness)
        // ->where('id', '!=', $id)
        // ->exists();
        // if ($attendance) {
        //     return response()->json([
        //         'status' => 404,
        //         'errors' => 'Strength/Weakness Already Exists'
        //     ]);
        // }
        // $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','strength'=>'string','weakness'=>'string', 'edited_by'=>'required|integer']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 404,
        //         'errors' => $validator->messages(),
        //     ]);
        // }


        $attendance = AttendanceEntry::findOrFail($id)->update($request->all());
        if ($attendance)
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
            $attendance = AttendanceEntry::destroy($id);
            if($attendance)    
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
}
