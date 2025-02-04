<?php

namespace App\Http\Controllers;

use App\Models\CallCreation;
// use App\Models\CustomerCreationProfile;
// use App\Models\CallType;
use App\Models\BusinessForecast;
use App\Models\BusinessForeCastStatus;
use App\Models\ProcurementType;
use App\Models\User;
use App\Models\CallLog;
use App\Models\CallFileSub;
use App\Models\CallHistory;
// use App\Models\CallLogFiles;
// use App\Models\CallLogFilesSub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Carbon\Carbon;
use App\Models\CallLogFiles;

class CallCreationController extends Controller
{


    public function index()
    {
        $call_log = DB::table('call_log_creations as clc')
            ->join('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
            ->join('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
            ->join('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
            ->join('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
            ->join('users as u', 'u.id', 'clc.executive_id')
            ->join('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
            ->select(
                'clc.callid',
                'cc.id',
                'cc.customer_name',
                'ct.id',
                'ct.name as callname',
                'bf.id',
                'bf.name as bizzname',
                'bfs.id',
                'bfs.status_name as bizzstatusname',
                'u.id',
                'u.name as username',
                'pt.id',
                'pt.name as proname',
                'clc.id',
                'clc.call_date',
                'clc.action',
                'clc.next_followup_date',
                'clc.close_date',
                'clc.additional_info',
                'clc.remarks',
            )
            ->get();
        if ($call_log)
            return response()->json([
                'status' => 200,
                'calllog' => $call_log
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();

        // if($request ->hasFile('file')){
        //     $file = $request->file('file');
        //     $filename_original = $file->getClientOriginalName();
        //     $fileName =intval(microtime(true) * 1000) . $filename_original;
        //     $file->storeAs('/uploads/CallCreation/CallLog/', $fileName, 'public');
        //     $mimeType =  $file->getMimeType();
        //     $filesize = ($file->getSize())/1000;
        //     $ext =  $file->extension();
        // }

        if ($user['userid']) {

            // $call_log = CallLog::where('customer_id', '=', $request->customer_id)->exists();
            // if ($call_log) {
            // return response()->json([
            //     'status' => 400,
            //     'message' => 'Call Log Already Exists!'
            // ]);
            // } 


            // $curryear = date('y');
            // $currmonth = date('m');

            $calldate = explode("-", $request->call_date);
            $curryear = substr($calldate[0], 2, 4);
            $currmonth = $calldate[1];
            $calseq_qry = CallLog::select('callid')->where('call_date', 'Like', '%' . substr($calldate[0], 2, 2) . '-' . $calldate[1] . '%')->orderby('id', 'DESC')->limit(1)->get();


            $call_id = null;
            if ($calseq_qry->isEmpty()) {
                // echo " - It is Empty - ";
                // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                $call_id = "CID-" . substr($calldate[0], 2, 2) . $calldate[1] . "00001";
            } else {
                // echo " - It is in else - ";
                $year = substr($calseq_qry[0]->callid, 4, 2);
                $month = substr($calseq_qry[0]->callid, 6, 2);
                $lastid = substr($calseq_qry[0]->callid, 8, 5);
                if ($year == $curryear) {
                    // echo " - It is in year == curryear - ";
                    if ($month == $currmonth) {
                        // echo " - It is in month == currmonth - ";
                        $call_id = "CID-" . $curryear . $currmonth . str_pad(($lastid + 1), 5, '0', STR_PAD_LEFT);
                        // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . ($lastid + 1)]);
                    } else {
                        // echo " - It is in month == currmonth  Else - ";
                        $call_id = "CID-" . $curryear . $currmonth . "00001";
                        // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                    }
                } else {
                    // echo " - It is in year == curryear Else - ";
                    $call_id = "CID-" . substr($calldate[0], 2, 2) . $calldate[1] . "00001";
                    // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
                }
            }
            $request->request->add(['callid' => $call_id]);
            $request->request->add(['created_by' => $user['userid']]);
            $request->request->add(['executive_id' => $user['userid']]);
            $request->request->remove('tokenid');
            if (!empty($request->next_followup_date)) {
                $request->request->add(['action' => 'next_followup']);
            }
            if (!empty($request->close_date)) {
                $request->request->add(['action' => 'close']);
            }

            $call_log_add = CallLog::firstOrCreate($request->all());
            $call_log_add->callid = $call_id;
            $call_log_add->save();
            $main_id = $call_log_add->id;

            $dateTime = date('Y-m-d H:i', strtotime($request->call_date));
            $call_history_add = new CallHistory;

            if($request->procurement_type_id === 'null')
            {
                $request->request->remove('procurement_type_id');
            }
            else{
                $call_history_add->procurement_type_id = $request->procurement_type_id;
            }
    
            return $call_history_add;
            $call_history_add->main_id = $main_id;
            $call_history_add->customer_id = $request->customer_id;
            // $call_history_add->call_date = $request->call_date;
            $call_history_add->call_date = $dateTime;
            $call_history_add->call_type_id = $request->call_type_id;
            $call_history_add->bizz_forecast_id = $request->bizz_forecast_id;
            $call_history_add->bizz_forecast_status_id = $request->bizz_forecast_status_id;
            $call_history_add->additional_info = $request->additional_info;
            $call_history_add->executive_id = $request->executive_id;
            
            $call_history_add->action = $request->action;
            $call_history_add->next_followup_date = $request->next_followup_date;
            $call_history_add->description = $request->description;
            $call_history_add->close_date = $request->close_date;
            $call_history_add->close_status_id = $request->close_status_id;
            $call_history_add->remarks = $request->remarks;
            $call_history_add->created_by = $user['userid'];
            $call_history_add->save();

            if ($call_history_add) {

                if ($request->hasFile('file')) {

                    $files = $request->file('file');
                    foreach ($files as $index => $call_file) {
                        $call_file_original = $call_file->getClientOriginalName();
                        $call_file_fileName = intval(microtime(true) * 1000) . $call_file_original;
                        $call_file->storeAs('CallCreation/CallLog/', $call_file_fileName, 'public');
                        $call_file_mimeType =  $call_file->getMimeType();
                        $call_file_filesize = ($call_file->getSize()) / 1000;
                        // $call_file_ext =  $call_file->extension();

                        // $data=(array) $request->all();   

                        $request->request->add(['createdby_userid' => $user['userid']]);
                        $request->request->add(['hasfilename' => $call_file_fileName]);
                        $request->request->add(['originalfilename' => $call_file_original]);
                        $request->request->add(['filetype' => $call_file_mimeType]);
                        $request->request->add(['filesize' => $call_file_filesize]);
                        $request->request->add(['mainid' => $call_log_add->id]);

                        return $request;

                        $call_log_add = CallLogFiles::firstOrCreate($request->only(['mainid', 'originalfilename', 'filetype', 'filesize', 'hasfilename', 'createdby_userid']));
                    }
                }

                if (($request->hasFile('file') && ($call_log_add))) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Call Log Form Created Succssfully!',
                        'mainid' => $call_log_add->id,
                        'callid' => $call_log_add->callid
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Unable to Save Files Now...!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Unable to Save Form Details Now...!'
                ]);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }


    public function show($id)
    {
        /******showing form CallHistory Controller show()******/
        // $show_call_log = DB::table('call_log_creations as clc')
        //     ->leftjoin('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
        //     ->leftjoin('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
        //     ->leftjoin('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
        //     ->leftjoin('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
        //     ->leftjoin('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
        //     ->leftjoin('users', 'clc.executive_id', 'users.id')
        //     ->where("clc.id", $id)
        //     ->select(
        //         'clc.executive_id as user_id',
        //         'clc.callid',
        //         'users.userName',
        //         'cc.id as cust_id',
        //         'cc.customer_name',
        //         'ct.id as call_id',
        //         'ct.name as callname',
        //         'bf.id as bizz_id',
        //         'bf.name as bizzname',
        //         'bfs.id as bizz_status_id',
        //         'bfs.status_name as bizzstatusname',
        //         'pt.id as proc_id',
        //         'pt.name as proname',
        //         'clc.id',
        //         'clc.call_date',
        //         'clc.action',
        //         'clc.next_followup_date',
        //         'clc.close_date',
        //         'clc.additional_info',
        //         'clc.remarks',
        //         'clc.close_status_id',
        //         // 'clc.filename',
        //     )
        //     ->get();

        // if ($show_call_log)
        //     return response()->json([
        //         'status' => 200,
        //         'showcall' => $show_call_log,
        //     ]);
        // else {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'The provided credentials are Invalid'
        //     ]);
        // }
    }

    public function update(Request $request, $id)
    {
        return $request;
        try {
            $user = Token::where('tokenid', $request->tokenid)->first();
            if ($user['userid']) {
                $call_log = CallLog::findOrFail($id);
                if (!$call_log) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Invalid Credentials..!'
                    ]);
                }
            }
            $request->request->add(['edited_by' => $user['userid']]);
            $request->request->remove('tokenid');
            if (!empty($request->next_followup_date)) {
                $request->request->add(['action' => 'next_followup']);
            }
            if (!empty($request->close_date)) {
                $request->request->add(['action' => 'close']);
            }

            $call_log_update = CallLog::findOrFail($id)->update($request->all());

            $call_log_id = CallLog::find($id);
            $main_id = $call_log_id->id;
            $call_history_id = DB::table('call_histories as ch')
                ->where('ch.main_id', $main_id)->latest('ch.id')->first();
            $id = $call_history_id->id;

            $call_history_update = CallHistory::findOrFail($id)->update($request->all());

            if ($call_history_update)
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
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'Invalid Credentials. Please Try again..!',
                "errormessage" => $error,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallCreation  $callCreation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $call_log_del = CallLog::destroy($id);
        if ($call_log_del)
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


    public function getBizzList($callTypeId)
    {

        $business_forecast_list = BusinessForecast::where("call_type_id", $callTypeId)->where("activeStatus", "=", "Active")->get();
        $bizzList = [];
        foreach ($business_forecast_list as $row) {
            $bizzList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'bizzlist' =>  $bizzList,

        ]);
    }

    public function getStatusList($bizzId)
    {
        $status_list = BusinessForeCastStatus::where("bizz_forecast_id", $bizzId)->where("active_status", "=", "Active")->get();
        $statusList = [];
        foreach ($status_list as $row) {
            $statusList[] = ["value" => $row['id'], "label" =>  $row['status_name']];
        }
        return  response()->json([
            'statuslist' =>  $statusList,
        ]);
    }

    public function getProcurementList()
    {
        $procurement_list = ProcurementType::where("active_status", "=", "Active")->get();
        $proList = [];
        foreach ($procurement_list as $row) {
            $proList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'procurementlist' =>  $proList,
        ]);
    }


    public function getUserList()
    {
        $user_list = User::get();
        $userList = [];
        foreach ($user_list as $row) {
            $userList[] = ["value" => $row['id'], "label" =>  $row['name']];
        }
        return  response()->json([
            'user' =>  $userList,
        ]);
    }


    public function download($fileName)
    {

        $doc = CallFileSub::find($fileName);

        if ($doc) {
            $fileName = $doc['hasfilename'];
            //$file = public_path()."'uploads/CallLogs/CallLogFiles/'".$fileName;
            $file = public_path('uploads/CallLogs/CallLogFiles/' . $fileName);
            // return $file;
            return response()->download($file);
        }
    }

    public function callfileupload(Request $request)
    {


        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $originalfileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hasfileName = $file->hashName();
            $filenameSplited = explode(".", $hasfileName);
            $filename2 = 'calllog' . time() . '.' . $filenameSplited[1];

            // return $filenameSplited;
            if ($filenameSplited[1] != $originalfileName) {
                $fileName = $filenameSplited[0] . "" . $originalfileName;
            } else {
                $fileName = $hasfileName;
            }
            //$file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            $destinationPath = 'uploads/CallLogs/CallLogFiles/';
            $result = $file->move($destinationPath, $hasfileName);

            $user = Token::where('tokenid', $request->tokenid)->first();
            $request->request->remove('tokenid');


            $get_id = CallLog::orderBy('id', 'desc')->first('id');

            $get = $get_id->id;

            $last_id = $get;


            $callFileSub = new CallFileSub;
            $callFileSub->mainid = $last_id;
            $callFileSub->filename = $filename2;
            $callFileSub->originalfilename = $originalfileName;
            $callFileSub->filetype = $fileType;
            $callFileSub->filesize = $fileSize;
            $callFileSub->hasfilename = $hasfileName;
            $callFileSub->createdby_userid = $user['userid'];
            $callFileSub->save();


            return response()->json([
                'status' => 200,
                'message' => 'Call Log Form Created Succssfully!'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }


    //return call id list as value label object based on selected customer_id and user id
    public function usersCallList(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        if ($user['userid']) {
            $docs = DB::table('call_log_creations')->where('customer_id', $request->id)->where('executive_id', $user['userid'])
                ->select('callid', 'id')
                ->orderBy('id', 'desc')
                ->get();

            if ($docs)
                return response()->json([
                    'status' => 200,
                    'docs' => $docs,
                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function getCallMainList($token)
    {
        $user = Token::where("tokenid", $token)->first();
        if ($user['userid']) {
            $call_log = DB::table('call_log_creations as clc')
                ->leftjoin('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
                ->leftjoin('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
                ->leftjoin('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
                ->leftjoin('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
                ->leftjoin('users as u', 'u.id', 'clc.executive_id')
                ->leftjoin('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
                ->select(
                    'clc.callid',
                    'cc.id',
                    'cc.customer_name',
                    'ct.id',
                    'ct.name as callname',
                    'bf.id',
                    'bf.name as bizzname',
                    'bfs.id',
                    'bfs.status_name as bizzstatusname',
                    'u.id',
                    'u.name as username',
                    'pt.id',
                    'pt.name as proname',
                    'clc.id',
                    'clc.call_date',
                    'clc.action',
                    'clc.next_followup_date',
                    'clc.close_date',
                    'clc.additional_info',
                    'clc.remarks',
                )
                ->where("clc.created_by", $user['userid'])
                ->get();

            // $query = str_replace(array('?'), array('\'%s\''), $call_log->toSql());
            // $query = vsprintf($query, $call_log->getBindings());

            if ($call_log)
                return response()->json([
                    'status' => 200,
                    'calllog' => $call_log
                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function CallBookTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if ($user['userid']) {
            $header = ['Call ID', 'Customer Name', 'Executive Name', 'Mode', 'Call Type', 'Started', 'Completed', 'Next Follow Up'];
            $accessor = ['callid', 'customer_name', 'username', 'mode', 'callname', 'call_date', 'close_date', 'next_followup_date'];

            $call_log = DB::table('call_log_creations as clc')
                ->leftjoin('customer_creation_profiles as cc', 'cc.id', 'clc.customer_id')
                ->leftjoin('call_types_mst as ct', 'ct.id', 'clc.call_type_id')
                ->leftjoin('business_forecasts as bf', 'bf.id', 'clc.bizz_forecast_id')
                ->leftjoin('business_forecast_statuses as bfs', 'bfs.id', 'clc.bizz_forecast_status_id')
                ->leftjoin('users as u', 'u.id', 'clc.executive_id')
                ->leftjoin('call_procurement_types as pt', 'pt.id', 'clc.procurement_type_id')
                ->select(
                    'clc.callid',
                    'cc.id',
                    'cc.customer_name',
                    'ct.id',
                    'ct.name as callname',
                    'bf.id',
                    'bf.name as bizzname',
                    'bfs.id',
                    'bfs.status_name as bizzstatusname',
                    'u.id',
                    'u.name as username',
                    'pt.id',
                    'pt.name as proname',
                    'clc.id',
                    'clc.call_date',
                    'clc.action',
                    'clc.next_followup_date',
                    'clc.close_date',
                    'clc.additional_info',
                    'clc.remarks',
                )
                ->where("clc.created_by", $user['userid'])
                ->get();


            if ($call_log)
                return response()->json([
                    'status' => 200,
                    'title' => 'CallBooking',
                    'header' => $header,
                    'accessor' => $accessor,
                    'data' => $call_log,

                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
        }
    }


    // callcount for dashboard and bdm users 
    // public function getCallCountAnalysis(Request $request)
    // {
    //    // try {
    //     $today = Carbon::now()->toDateString();

    //     $user = Token::where('tokenid', $request->tokenid)->first();
    //     $userid = $user['userid'];

    //     if ($userid) { 

    //         $role = DB::table('model_has_roles as m')
    //         ->leftJoin('roles as r', 'r.id', '=', 'm.role_id')
    //         ->where('m.model_id', $userid)
    //         ->select('r.id as id')
    //         ->first();

    // $roleid = $role->id;


    // if($roleid == 2) //for BDM Users
    // {  $todayCallsCount="hi";
    // $todayCallsCount = DB::table('calltobdms AS a')
    //        ->leftJoin('calltobdm_has_customers AS b', 'b.calltobdm_id', '=', 'a.id')
    //        ->where('a.user_id', '=', $userid)
    //        ->where('a.created_at', 'LIKE', "%$today%")
    //        ->distinct()
    //        ->count();
    //  $openingCallsCount =  DB::table('calltobdms as a')
    //        ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //        ->leftJoin('call_log_creations as c', function($join) {
    //            $join->on('c.customer_id', '=', 'b.customer_id')
    //             ->where('c.customer_id', '!=', ''); })
    //               ->where('a.user_id', '=', $userid)
    //                 ->where('c.call_date', 'NOT LIKE', "%$today%")
    //                 ->where('c.action', '!=', 'close')
    //               ->distinct()
    //                ->count('c.id');
    //  $attendedCallsCount= DB::table('calltobdms as a')
    //                ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //                ->leftJoin('call_log_creations as c', function($join) {
    //                    $join->on('c.customer_id', '=', 'b.customer_id')
    //                 ->where('c.customer_id', '!=', ''); })
    //                  ->where('a.user_id', '=', $userid)
    //                ->where('c.call_date', 'LIKE', "%$today%")
    //                 ->distinct()
    //                 ->count('c.id');
    //  $ClosedCallsCount = DB::table('calltobdms as a')
    //     ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //     ->leftJoin('call_log_creations as c', function($join) {
    //         $join->on('c.customer_id', '=', 'b.customer_id')
    //             ->where('c.customer_id', '!=', '');
    //     }) ->where('a.user_id', '=', $userid)
    //        ->where('c.action', '=', 'close')
    //          ->where('c.customer_id', '!=', '')
    //         ->distinct()
    //         ->count('c.id');
    //      $overduecallcount = DB::table('calltobdms as a')
    //         ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //         ->leftJoin('call_log_creations as c', function($join) {
    //             $join->on('c.customer_id', '=', 'b.customer_id')
    //                 ->where('c.customer_id', '!=', '');
    //         })->where('a.user_id', '=', $userid)
    //         ->where('c.action', '=', 'next_followup')
    //         ->where('c.customer_id', '!=', '')
    //         ->distinct()
    //         ->count('c.id');

    // }

    // else{//Other Users
    // //1.
    //     $todayCallsCount = DB::table('calltobdms AS a')
    //                  ->leftJoin('calltobdm_has_customers AS b', 'b.calltobdm_id', '=', 'a.id')
    //                   ->where('a.created_userid', '=', $userid)
    //                   ->where('a.created_at', 'LIKE', "%$today%")
    //                   ->distinct()
    //                   ->count();
    //  //2.
    //     $openingCallsCount =  DB::table('calltobdms as a')
    //                   ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //                   ->leftJoin('call_log_creations as c', function($join) {
    //                       $join->on('c.customer_id', '=', 'b.customer_id')
    //                           ->where('c.customer_id', '!=', '');})
    //                   ->where('a.created_userid', '=', $userid)
    //                   ->where('c.call_date', 'NOT LIKE', "%$today%")
    //                   ->where('c.action', '!=', 'close')
    //                   ->distinct()
    //                   ->count('c.id');
    //   //3.
    //     $attendedCallsCount= DB::table('calltobdms as a')
    //                   ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //                   ->leftJoin('call_log_creations as c', function($join) {
    //                       $join->on('c.customer_id', '=', 'b.customer_id')
    //                           ->where('c.customer_id', '!=', ''); })
    //                             ->where('a.created_userid', '=', $userid)
    //                             ->where('c.call_date', 'LIKE', "%$today%")
    //                             ->distinct()
    //                              ->count('c.id');
    //     // 4.
    //      $ClosedCallsCount = DB::table('calltobdms as a')
    //     ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //     ->leftJoin('call_log_creations as c', function($join) {
    //         $join->on('c.customer_id', '=', 'b.customer_id')
    //             ->where('c.customer_id', '!=', ''); })
    //     ->where('a.created_userid', '=', $userid)
    //     ->where('c.action', '=', 'close')
    //     ->where('c.customer_id', '!=', '')
    //     ->distinct()
    //     ->count('c.id');
    //     // 5.
    //     $overduecallcount = DB::table('calltobdms as a')
    //     ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
    //     ->leftJoin('call_log_creations as c', function($join) {
    //         $join->on('c.customer_id', '=', 'b.customer_id')
    //             ->where('c.customer_id', '!=', '');})
    //          ->where('a.created_userid', '=', $userid)
    //         ->where('c.action', '=', 'next_followup')
    //          ->where('c.customer_id', '!=', '')
    //       ->distinct()
    //          ->count('c.id');
    // }

    //         return response()->json([
    //             'status' => 200,
    //             'userid'=>$userid,
    //             'todaycallCount' => $todayCallsCount, //how many calls assigned bdm to calltobdm-has_customers
    //             'openingCallCount' => $openingCallsCount, //not closed calls except today
    //             'completedCallCount' => $ClosedCallsCount,  //completed calls
    //             'attendedCallsCount' => $attendedCallsCount,//how many calls received as per today only
    //             'overduecallcount' => $overduecallcount,//next foollow up calls
    //            'query' => $roleid,
    //         ]);
    //    // }
    // }
    //   //  } catch (\Exception $ex) {

    //         // return response()->json([
    //         //     'status' => 204,
    //         //     'message' => "Somthing Wrong",
    //         //     'error' => $ex
    //         // ]);
    //    // }
    // }
    public function getCallCountAnalysis(Request $request)
    {
        // try {
        $today = Carbon::now()->toDateString();
        $currentDate = now()->format('Y-m-d');

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($userid) {

            $role = DB::table('model_has_roles as m')
                ->leftJoin('roles as r', 'r.id', '=', 'm.role_id')
                ->where('m.model_id', $userid)
                ->select('r.id as id')
                ->first();

            $roleid = $role->id;


            if ($roleid == 2) //for BDM Users
            {
                $todayCallsCount = DB::table('calltobdms AS a')
                    ->leftJoin('calltobdm_has_customers AS b', 'b.calltobdm_id', '=', 'a.id')
                    ->where('a.user_id', '=', $userid)
                    ->where('b.created_at', 'LIKE', '%' . date('Y-m-d') . '%')
                    ->distinct()
                    ->count();
                $openingCallsCount1 = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->where(function ($query) {
                        $query->where('b.created_at', 'LIKE', '%' . date('Y-m-d') . '%')
                            ->orWhere('b.created_at', '<=',  DB::raw('now()'));
                    })
                    ->where('a.user_id', '=', $userid)
                    ->distinct('b.id')
                    ->count();

                $attendedCallsCount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('a.user_id', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.user_id', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=',  DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE', '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');
                $openingCallsCount = $openingCallsCount1 - $attendedCallsCount;

                $ClosedCallsCount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('c.action', '=', 'close')
                            ->where('a.user_id', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.user_id', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=',  DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE',  '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');

                $overduecallcount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('c.action', '=', 'next_followup')
                            ->where('a.user_id', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.user_id', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=', DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE', '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');
            } else { //Other Users
                //1.
                $todayCallsCount = DB::table('calltobdms AS a')
                    ->leftJoin('calltobdm_has_customers AS b', 'b.calltobdm_id', '=', 'a.id')
                    ->where('a.created_userid', '=', $userid)
                    ->where('b.created_at', 'LIKE', '%' . date('Y-m-d') . '%')
                    ->distinct()
                    ->count();

                $openingCallsCount1 = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->where(function ($query) {
                        $query->where('b.created_at', 'LIKE', '%' . date('Y-m-d') . '%')
                            ->orWhere('b.created_at', '<=', DB::raw('now()'));
                    })
                    ->where('a.created_userid', '=', $userid)
                    ->distinct('b.id')
                    ->count();

                $attendedCallsCount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('a.created_userid', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.created_userid', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=',  DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE', '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');
                $openingCallsCount = $openingCallsCount1 - $attendedCallsCount;

                $ClosedCallsCount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('c.action', '=', 'close')
                            ->where('a.created_userid', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.created_userid', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=',  DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE', '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');


                $overduecallcount = DB::table('calltobdms as a')
                    ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
                    ->leftJoin('call_log_creations as c', function ($join) use ($userid) {
                        $join->on('c.customer_id', '=', 'b.customer_id')
                            ->where('c.action', '=', 'next_followup')
                            ->where('a.created_userid', '=', DB::raw("c.executive_id"));
                    })
                    ->where('a.created_userid', '=', $userid)
                    ->where('c.customer_id', '!=', '')
                    ->where(function ($query) {
                        $query->where('c.call_date', '<=',  DB::raw('now()'))
                            ->orWhere('c.call_date', 'LIKE', '%' . date('Y-m-d') . '%');
                    })
                    ->distinct()
                    ->count('c.id');
            }




            return response()->json([
                'status' => 200,
                'userid' => $userid,
                'todaycallCount' => $todayCallsCount, //how many calls assigned bdm to calltobdm-has_customers
                'openingCallCount' => $openingCallsCount, //not closed calls except today
                'completedCallCount' => $ClosedCallsCount,  //completed calls
                'attendedCallsCount' => $attendedCallsCount, //how many calls received as per today only
                'overduecallcount' => $overduecallcount, //next foollow up calls
                'query' => $roleid,
                'today' => $currentDate,
            ]);
            // }
        }
        //  } catch (\Exception $ex) {

        // return response()->json([
        //     'status' => 204,
        //     'message' => "Somthing Wrong",
        //     'error' => $ex
        // ]);
        // }
    }

    /////////////////////////////////BACKUP/////////////////////////////////////////////
    // public function store(Request $request)
    // {
    //     $user = Token::where('tokenid', $request->tokenid)->first();
    //     if ($user['userid']) {
    //         // $call_log = CallLog::where('customer_id', '=', $request->customer_id)->exists();
    //         // if ($call_log) {
    //         // return response()->json([
    //         //     'status' => 400,
    //         //     'message' => 'Call Log Already Exists!'
    //         // ]);
    //         // } 


    //         // $curryear = date('y');
    //         // $currmonth = date('m');

    //         $calldate = explode("-", $request->call_date);
    //         $curryear = substr($calldate[0], 2, 4);
    //         $currmonth = $calldate[1];
    //         $calseq_qry = CallLog::select('callid')->where('call_date', 'Like', '%' . substr($calldate[0], 2, 2) . '-' . $calldate[1] . '%')->orderby('id', 'DESC')->limit(1)->get();


    //         $call_id = null;
    //         if ($calseq_qry->isEmpty()) {
    //             // echo " - It is Empty - ";
    //             // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
    //             $call_id = "CID-" . substr($calldate[0], 2, 2) . $calldate[1] . "00001";
    //         } else {
    //             // echo " - It is in else - ";
    //             $year = substr($calseq_qry[0]->callid, 4, 2);
    //             $month = substr($calseq_qry[0]->callid, 6, 2);
    //             $lastid = substr($calseq_qry[0]->callid, 8, 5);
    //             if ($year == $curryear) {
    //                 // echo " - It is in year == curryear - ";
    //                 if ($month == $currmonth) {
    //                     // echo " - It is in month == currmonth - ";
    //                     $call_id = "CID-" . $curryear . $currmonth . str_pad(($lastid + 1), 5, '0', STR_PAD_LEFT);
    //                     // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . ($lastid + 1)]);
    //                 } else {
    //                     // echo " - It is in month == currmonth  Else - ";
    //                     $call_id = "CID-" . $curryear . $currmonth . "00001";
    //                     // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
    //                 }
    //             } else {
    //                 // echo " - It is in year == curryear Else - ";
    //                 $call_id = "CID-" . substr($calldate[0], 2, 2) . $calldate[1] . "00001";
    //                 // $request->request->add(['callid' => "CID-" . $curryear . $currmonth . "00001"]);
    //             }
    //         }
    //         $request->request->add(['callid' => $call_id]);
    //         $request->request->add(['created_by' => $user['userid']]);
    //         $request->request->add(['executive_id' => $user['userid']]);
    //         $request->request->remove('tokenid');
    //         if (!empty($request->next_followup_date)) {
    //             $request->request->add(['action' => 'next_followup']);
    //         }
    //         if (!empty($request->close_date)) {
    //             $request->request->add(['action' => 'close']);
    //         }

    //         $call_log_add = CallLog::firstOrCreate($request->all());
    //         $call_log_add->callid = $call_id;
    //         $call_log_add->save();
    //         if ($call_log_add) {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Call Log Form Created Succssfully!',
    //                 'mainid' => $call_log_add->id,
    //                 'callid' => $call_log_add->callid
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Provided Credentials are Incorrect!'
    //         ]);
    //     }
    // }


}
