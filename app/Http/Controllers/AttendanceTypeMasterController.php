<?php

namespace App\Http\Controllers;

use App\Models\AttendanceTypeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class AttendanceTypeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AttendanceTypelist = DB::table('attendance_type_masters')->where('status','Active')
        ->select('*')
        // ->select('id','attendance_type','description','status')
        ->get();

        return response()->json([
            "attendanceTypelist" => $AttendanceTypelist
        ]);

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

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid)
        {

        
            


             $AttendanceTypeCreation = new  AttendanceTypeMaster;

             $AttendanceTypeCreation->attendance_type = $request->attendance_type;

             $AttendanceTypeCreation->description = $request->description;

             $AttendanceTypeCreation->status = $request->status;

             $AttendanceTypeCreation->createdby =  $userid;

             $AttendanceTypeCreation->save();

        }
        if($AttendanceTypeCreation)
        {


            return response()->json([
                "status"=>200,
                "message"=>"AttendanceType created Succssfully!",
                "id"=> $AttendanceTypeCreation->id 
            ]);



        }else{

            return response()->json([
                "status"=>400,
                "message"=>"Unable to Save !"

            ]);


        }
      

       

        


        
       
        
       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceTypeMaster  $attendanceTypeMaster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $AttendanceTypeCreation = AttendanceTypeMaster::find($id);
        if ($AttendanceTypeCreation)
        {
            return response()->json([
                'status' => 200,
                'AttendanceTypeData' => $AttendanceTypeCreation
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
     * @param  \App\Models\AttendanceTypeMaster  $attendanceTypeMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceTypeMaster $attendanceTypeMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceTypeMaster  $attendanceTypeMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];


        try{
            
            if($userid)
        {

            $AttendanceTypeCreation = AttendanceTypeMaster::findOrFail($id);
            
            

                $AttendanceTypeCreation->attendance_type = $request->attendance_type;
                $AttendanceTypeCreation->description = $request->description;
                $AttendanceTypeCreation->status = $request->status;
                $AttendanceTypeCreation->updatedby = $userid;
                $AttendanceTypeCreation->save();


                if($AttendanceTypeCreation){

                    return response()->json([
                        "status"=>200,
                        "message"=>  "Updated Successfully!"
                    ]);

                }
                else{

                    return response()->json([
                        "status"=>400,
                        "message"=>  "Unable to Update!"
                    ]);

                }


               
    
            
           

        }
       

        }
        catch(\Illuminate\Database\QueryException $ex)
        {

            $errors = $ex->getMessage();

            return response()->json([
                "status"=>200,
                "message"=>  $errors
            ]);



        }

        

      

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceTypeMaster  $attendanceTypeMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $deleteAttendanceType = AttendanceTypeMaster::destroy($id);
            if($deleteAttendanceType)
            {return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.',
                "errormessage" => "",
            ]);}
        }
        catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage();

            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error
            ]);
        }
    }
}
