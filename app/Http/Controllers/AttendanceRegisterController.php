<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use App\Models\LeaveRegister;
use App\Models\LeaveRegistersFile;
use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\AttendanceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;


class AttendanceRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavereglist = [];
        $leave_register = LeaveRegister::orderBy('created_at', 'desc')->get();

        foreach ($leave_register as $row) {
            $userdata = User::where('id', $row->user_id)->first();
            $attendancetype = AttendanceType::where('id', $row->attendance_type_id)->first();
            $leavereglist[] = ['id' => $row->id, 'user_name' => $userdata->userName, 'leavedate' => $row->from_date, 'leavetype' => $attendancetype->attendanceType, 'reason' => $row->reason];
        }


        if ($leave_register)
            return response()->json([
                'status' => 200,
                'leaveregister' => $leavereglist
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

   
    public function store(Request $request)
    {
        
        $user = Token::where('tokenid', $request->tokenid)->first();

        $userid = $user['userid'];
        if ($user) {

            $leave_exist = LeaveRegister::where('user_id', '=', $request->user_id)
                                        ->where('attendance_type_id','=', $request->attendance_type_id)
                                        ->where('from_date','=', $request->from_date)
                                        ->where('to_date','=', $request->to_date)
                                        ->exists();
            if ($leave_exist) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Employee Leave Already Exists!!'
                ]);
            }

            $tabel = new LeaveRegister;
            $tabel->user_id = $request->user_id;
            $tabel->attendance_type_id = $request->attendance_type_id;
            $fromdate = date('Y-m-d', strtotime($request->from_date));
            $todate = date('Y-m-d', strtotime($request->to_date));
            $tabel->from_date = $fromdate;
            $tabel->to_date = $todate;
            $tabel->start_time = $request->start_time;
            $tabel->reason = $request->reason;
            $tabel->latitude = $request->latitude;
            $tabel->longitude = $request->longitude;
            $tabel->created_by = $userid;
            $tabel->save();
            $inserted_id = $tabel->id;
            if ($inserted_id && $request->hasFile('file')) {
                $file_list = [];
                foreach ($request->file('file') as $file) {
                    $call_file_original = $file->getClientOriginalName();
                    $call_file_fileName = intval(microtime(true) * 1000) . $call_file_original;
                    $file->storeAs('attendanceregisterfiles/', $call_file_fileName, 'public');
                    $call_file_mimeType =  $file->getMimeType();
                    $call_file_filesize = ($file->getSize());
                    $another_table = new LeaveRegistersFile; //store file details in subtable
                    $another_table->mainid = $inserted_id;
                    $another_table->filename = $call_file_original;
                    $another_table->filetype = $call_file_mimeType;
                    $another_table->filesize = $call_file_filesize;
                    $another_table->hasfilename = $call_file_fileName;
                    $another_table->created_by = $userid;
                    $another_table->save();
                }
                if ($another_table || $inserted_id) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Attendance Entry Added Successfully!',
                        'id'=>$inserted_id
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Provided Credentials are Incorrect!'
                    ]);
                }
            }else{
                if ($inserted_id) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Attendance Entry Added Successfully!',
                        'id'=>$inserted_id
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Provided Credentials are Incorrect!'
                    ]);
                }
            }

        }
    }

   


    public function show($id)
    {
        //     $show_attendance = DB::table('leave_registers as lr')
        //      ->join('leave_registers_files as lrf', 'lrf.mainid', 'lr.id')
        //      ->join('attendance_types as at', 'at.id', 'lr.attendance_type_id')
        //      ->join('users as u', 'u.id', 'lr.user_id')

        //     ->where('lr.id', $id)
        //     ->select(
        //         'lr.id as lrid',
        //         'lrf.mainid',
        //         'lr.user_id',
        //         'u.userName',
        //         'at.attendanceType',
        //         'lr.from_date',
        //         'lr.to_date',
        //         'lr.start_time',
        //         'lr.reason',
        //         'lrf.filename',
        //         'lrf.*'
        //     )   
        //     ->latest('lr.id')->first();
        //    //->get();

        $show_attendance = LeaveRegister::find($id);
        $show_files = LeaveRegistersFile::where('mainid', $id)->get();

        if ($show_attendance && $show_files)
            return response()->json([
                'status' => 200,
                'showattendance' => $show_attendance,
                'attendanceFiles' => $show_files,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    /**********************************UPDATE************************************************/
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        // $user = Token::where('tokenid', $request->tokenid)->first(); 


        if ($request->hasFile('file')) {
            // $file = $request->file('file');


            // $hasFileName = $file->hashName();
            // $originalFileName = $file->getClientOriginalName();
            // $fileType = $file->getClientOriginalExtension();
            // $fileSize = $file->getSize();


            // $leaveRegistersFile = LeaveRegistersFile::where('mainid', $id)->first();
            // // $destinationPath = 'uploads/attendanceregisterfiles/'. $leaveRegistersFile->hasfilename;
            // // unlink($destinationPath);

            // $leaveRegistersFile->hasfilename = $hasFileName;
            // $leaveRegistersFile->filename = $originalFileName;
            // $leaveRegistersFile->filetype = $fileType;
            // $leaveRegistersFile->filesize = $fileSize;
            // $leaveRegistersFile->save();
            // $result = $file->move('uploads/attendanceregisterfiles/',   $leaveRegistersFile->hasfilename);
            foreach ($request->file('file') as $file) {
                $call_file_original = $file->getClientOriginalName();
                $call_file_fileName = intval(microtime(true) * 1000) . $call_file_original;
                $file->storeAs('attendanceregisterfiles/', $call_file_fileName, 'public');
                $call_file_mimeType =  $file->getMimeType();
                $call_file_filesize = ($file->getSize());
                $another_table = new LeaveRegistersFile; //store file details in subtable
                $another_table->mainid = $id;
                $another_table->filename = $call_file_original;
                $another_table->filetype = $call_file_mimeType;
                $another_table->filesize = $call_file_filesize;
                $another_table->hasfilename = $call_file_fileName;
                $another_table->created_by = $user['userid'];
                $another_table->save();
            }
        }

        $attendanceUpdate = LeaveRegister::findOrFail($id);
        $attendanceUpdate->user_id = $request->user_id;
        $attendanceUpdate->attendance_type_id = $request->attendance_type_id;
        $fromdate = date('Y-m-d', strtotime($request->from_date));
        $todate = date('Y-m-d', strtotime($request->to_date));
        $attendanceUpdate->from_date = $fromdate;
        $attendanceUpdate->to_date = $todate;
        $attendanceUpdate->start_time = $request->start_time;
        $attendanceUpdate->reason = $request->reason;
        $attendanceUpdate->edited_by = $user['userid'];
        $attendanceUpdate->save();

        // $attendanceUpdate = LeaveRegister::findOrFail($id)->update($request->all());

        if ($attendanceUpdate) {
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!",
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Sorry, Failed to Update, Try again later"
            ]);
        }
    }
    /***************************************************************************************/


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        try {
            $leaveregister = LeaveRegister::destroy($id);
            if ($leaveregister) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.',
                    "errormessage" => "",
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();

            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    // public function UserList(Request $request)
    // {
    //     $userlist = [];
    //     $user = Token::where('tokenid', $request->tokenid)->first();   
    //     $userid = $user['userid'];
    //    if($userid){
    //     $userdata = User::where('id',$userid)->first();
    //     $userlist[] =['value'=>$userdata->id,'label'=>$userdata->userName];
    //     return response()->json([
    //         'userlist'=>$userlist
    //     ]);
    //    }
    // }

    public function download(Request $request)
    {

        $doc = LeaveRegistersFile::where('id',$request->id)->where('filename',$request->fileName)->first();

        if ($doc) {
            $fileName = $doc['hasfilename'];
            //$file = public_path()."'uploads/attendanceregisterfiles/'".$fileName;
            $file = public_path('uploads/attendanceregisterfiles/' . $fileName);
            // return $file;
            return response()->download($file);
        }
    }

    public function destroyFile($id)
    {
        try {
            $document = LeaveRegistersFile::find($id);
            $filename = $document['hasfilename'];
            $file_path = public_path() . "/uploads/attendanceregisterfiles/" . $filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $attendance_file_del = LeaveRegistersFile::destroy($id);
            if ($attendance_file_del)
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
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 400,
                'message' => 'The Provided Credentials are Incorrect.',
                'error' => $ex->getMessage()
            ]);
        }
    }

    public function getFilesList(Request $request)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();
        if ($user['userid']) {
            $show_files = LeaveRegistersFile::where('mainid', $request->id)->get();

            if ($show_files) {
                return response()->json([
                    'status' => 200,
                    'attendanceFiles' => $show_files,
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "Incorrect Credentials..!"
                ]);
            }
        }
    }

    public function getEmployeeLeaveListOLD(Request $request)
    {
        
        $userID = $request->user_id;
        $roleID = $request->role_id;
        
        if(empty($request->from_date))
        {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
           
           
        }
        else
        {
        //    return 'from date = '.$request->from_date;
            $date = Carbon::createFromFormat('m-Y', $request->from_date);
            $year = $date->year;
            $month = $date->month;
           
          
        }
    
        $user_list = [];
        $users = User::where('userType','!=',1)->get();
        foreach($users as $row)
        {
            $user_list[] =  ['id'=>$row->id,'name'=>$row->name]; 
        }
    
        $leave = DB::table('leave_registers as lr')
                ->join('users as u','u.id','lr.user_id')
                ->join('roles as r','r.id','u.userType')
                ->join('attendance_types as at','at.id','lr.attendance_type_id')
                ->select(
                    'lr.user_id',
                    'lr.attendance_type_id',
                    'at.attendanceType',
                    'at.icon_class',
                    'lr.from_date',
                    'lr.to_date',
                    'lr.start_time',
                    'lr.reason',
                    'r.id as role_ID',
                );
                   
                if($userID)
                {
                    $leave->where('lr.user_id',$userID)->where('lr.user_id','!=',1);
                }
                if($roleID)
                {
                    $leave->where('r.id',$roleID)->where('r.id','!=',1);
                }
                
                    $leave->whereMonth('lr.from_date','=',$month);
                    $leave->whereYear('lr.from_date','=',$year);
               
                    $leave = $leave->get();
              
        $result = [];
        foreach ($leave as $row) 
        {
            $user_id = $row->user_id;
            $result[$user_id][] = [
                'user_id' => $user_id,
                'attendance_type_id' => $row->attendance_type_id,
                'attendanceType' =>$row->attendanceType,
                'icon_class' =>$row->icon_class,
                'from_date' => $row->from_date,
                'to_date' => $row->to_date,
                'start_time' => $row->start_time,
                'reason' => $row->reason,
                'role' => $row->role_ID,
            ];
        }
                $holidaylist = []; 
                $holiday = Holiday::orderBy('created_at', 'ASC')
                        ->whereMonth('date',$month)
                        ->whereYear('date',$year)
                        ->get();
        
                foreach($holiday as $row)
                {
                    $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks'=>$row->remarks]; 
                }
    
        if ($holiday)
        return response()->json([
            'status' => 200,
            'userlist' => $user_list,
            'result' => $result,
            'holiday' => $holidaylist,
        ]);
        else 
        {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function getEmployeeLeaveList(Request $request)
    {
        $userID = $request->user_id; //selected user id
        $roleID = $request->role_id;
        $user = Token::where('tokenid', $request->token)->first();
        
        $userRoleID = User::find($user['userid']);
        // $userRole = Role::find($userRoleID['userType']);
        // return $userRoleID;
        
        if(empty($request->from_date))
        {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
        }
        else
        {
            $date = Carbon::parse($request->from_date);
            //$date = Carbon::createFromFormat('m-Y', $request->from_date);
            $year = $date->year;
            $month = $date->month;
        }
    
        $user_list = [];
        // $userListModal=new User;
        // $userListQuery='';
        // if(empty($userID))
        // {
        //     $userListQuery="where('userType','!=',1)";
        // }
        // else{
        //     $userListQuery="where('userType','!=',1)";
        // }
        // if(empty($roleID))
        // {
        //     $userListQuery = 
        // }
        // else{
        //     $userListQuery = where('userType',$roleID);
        // }



        if(empty($userID)){
            if(empty($roleID))
            {
                // if($userRoleID['userType'] === 1)
                // {
                //     $users = User::where('userType','!=',1)->where('userType',$roleID)->get();
                // }
                // else{
                    $users = User::where('userType','!=',1)->get();
                // }
            }
            else{
                // if($userRoleID['userType'] === 1)
                // {
                    $users = User::where('userType','!=',1)->where('userType',$roleID)->get();
                // }
                // else{
                //     $users = User::where('userType','!=',1)->get();
                // }
                
            }
        }
        else{
            if(empty($roleID))
            {
                if($userRoleID['userType'] === 1)
                {
                    $users = User::where('id',$userID)->get();
                }
                else{
                    $users = User::where('id',$userID)->where('userType','!=',1)->get();
                }
            }
            else{
                // if($userRoleID['userType'] === 1)
                // {
                //     $users = User::where('id',$userID)->get();
                // }
                // else{
                    $users = User::where('id',$userID)->where('userType','!=',1)->get();
                // }
            }
        }
       
        foreach($users as $row)
        {
            $user_list[] =  ['id'=>$row->id,'name'=>$row->name]; 
            
        }
       
        $leave = DB::table('leave_registers as lr')
                ->join('users as u','u.id','lr.user_id')
                ->join('roles as r','r.id','u.userType')
                ->join('attendance_types as at','at.id','lr.attendance_type_id')
                ->select(
                    'lr.user_id',
                    'lr.attendance_type_id',
                    'at.attendanceType',
                    'at.icon_class',
                    'lr.from_date',
                    'lr.to_date',
                    'lr.start_time',
                    'lr.reason',
                    'r.id as role_ID'
                );
                   
                if($userID)
                {
                    $leave->where('lr.user_id',$userID)->where('lr.user_id','!=',1);
                }
                if($roleID)
                {
                    if($userRoleID['userType']  === 1)
                    {
                        $leave->where('r.id',$roleID);
                    }
                    else{
                        $leave->where('r.id',$roleID)->where('r.id','!=',1);
                    }
                    
                }
                
                    $leave->whereMonth('lr.from_date','=',$month);
                    $leave->whereYear('lr.from_date','=',$year);
                    $leave = $leave->get();
              
        $result = [];
        foreach ($leave as $row) 
        {
            $user_id = $row->user_id;
            $result[$user_id][] = [
                'user_id' => $user_id,
                'attendance_type_id' => $row->attendance_type_id,
                'attendanceType' =>$row->attendanceType,
                'icon_class' =>$row->icon_class,
                'from_date' => $row->from_date,
                'to_date' => $row->to_date,
                'start_time' => $row->start_time,
                'reason' => $row->reason,
                'role' => $row->role_ID,
            ];
        }
                $holidaylist = []; 
                $holiday = Holiday::orderBy('created_at', 'ASC')
                        ->whereMonth('date',$month)
                        ->whereYear('date',$year)
                        ->get();
        
                foreach($holiday as $row)
                {
                    $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks'=>$row->remarks]; 
                }
    
        if ($holiday)
        return response()->json([
            'status' => 200,
            'userlist' => $user_list,
            'result' => $result,
            'holiday' => $holidaylist,
        ]);
        else 
        {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    

    public function userbasedindex(Request $request)
    {
        
        $user = Token::where('tokenid',$request->tokenid)->first();

        if($user['userid'])
        {  
            if( $user['userid']==1) //if logged user is a admin user
            {
                $leave_register = LeaveRegister::orderBy('created_at', 'desc')->get();
            }
            else{  //if logged user is not an admin user
                $leave_register = LeaveRegister::where('user_id',$user['userid'])->orderBy('created_at', 'desc')->get();
            }
        $leavereglist = [];
        

        foreach ($leave_register as $row) {
            $userdata = User::where('id', $row->user_id)->first();
            $attendancetype = AttendanceType::where('id', $row->attendance_type_id)->first();
            $leavereglist[] = ['id' => $row->id, 'user_name' => $userdata->userName, 'leavedate' => $row->from_date, 'leavetype' => $attendancetype->attendanceType, 'reason' => $row->reason];
        }


        if ($leave_register)
            return response()->json([
                'status' => 200,
                'leaveregister' => $leavereglist
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    else{
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
    }


    public function AttendanceMaster(){
        $accessor=[];
        $header=['Date & Time ','User Name','Attendance Type','Action'];
        $leavereglist = []; 
        $leave_register = LeaveRegister::orderBy('created_at', 'desc')->get();

        foreach($leave_register as $row)
        {
            $userdata = User::where('id',$row->user_id)->first();
            $attendancetype = AttendanceType::where('id',$row->attendance_type_id)->first();
            $leavereglist[] = ['id'=>$row->id,'user_name'=>$userdata->userName,'leavedate'=>$row->from_date,'leavetype'=>$attendancetype->attendanceType,'reason'=>$row->reason]; 

        }
        foreach($leavereglist[0] as $key => $value )
        {

           if($key === 'leavedate' || $key === 'user_name' || $key === 'leavetype')
           {
            $accessor[]=$key;
           }               
           }
    
        if ($leave_register)
            return response()->json([
                'status' => 200,
                'data' => $leavereglist,
                'header'=> $header,
                'title'=>'Attendance Entry List',
                'accessor'=>  $accessor,
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }       
    }

    
    public function AttendanceMasterReport(Request $request)
    {
        $header = ['Employee Name'];
        $validator = Validator::make($request->all(), [
            
            'from_date' => 'required',
            'user_id' => 'required',
            'role_id' => 'required',
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 422);
        }

        
        $userID = $request->user_id;
        $roleID = $request->role_id;
        
        if(empty($request->from_date))
        {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
           
           
        }
        else
        {
            $date = Carbon::parse($request->from_date);
            // $date = Carbon::createFromFormat('m-Y',$datestring);
            $year = $date->year;
            $month = $date->month;
           
           
          
        }
    
        $user_list = [];
        if(empty($userID)){
            $users = User::where('userType','!=',1)->get();
        }
        else{
            $users = User::where('id',$userID)->where('userType','!=',1)->get();
           
        }
       
        foreach($users as $row)
        {
            $user_list[] =  ['id'=>$row->id,'name'=>$row->name]; 
            
        }
       
        $leave = DB::table('leave_registers as lr')
                ->join('users as u','u.id','lr.user_id')
                ->join('roles as r','r.id','u.userType')
                ->join('attendance_types as at','at.id','lr.attendance_type_id')
                ->select(
                    'lr.user_id',
                    'lr.attendance_type_id',
                    'at.attendanceType',
                    'at.icon_class',
                    'lr.from_date',
                    'lr.to_date',
                    'lr.start_time',
                    'lr.reason',
                    'r.id as role_ID',
                );
                   
                if($userID)
                {
                    $leave->where('lr.user_id',$userID)->where('lr.user_id','!=',1);
                }
                if($roleID)
                {
                    $leave->where('r.id',$roleID)->where('r.id','!=',1);
                }
                
                    $leave->whereMonth('lr.from_date','=',$month);
                    $leave->whereYear('lr.from_date','=',$year);
                    $leave = $leave->get();
              
        $result = [];
        foreach ($leave as $row) 
        {
            $user_id = $row->user_id;
            $result[$user_id][] = [
                'user_id' => $user_id,
                'attendance_type_id' => $row->attendance_type_id,
                'attendanceType' =>$row->attendanceType,
                'icon_class' =>$row->icon_class,
                'from_date' => $row->from_date,
                'to_date' => $row->to_date,
                'start_time' => $row->start_time,
                'reason' => $row->reason,
                'role' => $row->role_ID,
            ];
        }
                $holidaylist = []; 
                $holiday = Holiday::orderBy('created_at', 'ASC')
                        ->whereMonth('date',$month)
                        ->whereYear('date',$year)
                        ->get();
        
                foreach($holiday as $row)
                {
                    $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks'=>$row->remarks]; 
                }

                foreach($user_list[0] as $key => $value ){

                   if($key === 'name'){
                    $accessor[]=$key;
                   }
                       
                   }
                  
        if ($holiday)
        return response()->json([
            'status' => 200,
            'data' =>  $user_list,
            'header'=> $header,
            'title'=>'Attendance Report',
            'accessor'=>  $accessor,
            'result' => $result,
            'holiday' => $holidaylist,
        ]);
        else 
        {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
        
    }
}
