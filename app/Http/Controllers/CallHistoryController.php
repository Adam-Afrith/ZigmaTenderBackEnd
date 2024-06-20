<?php

namespace App\Http\Controllers;

use App\Models\CallHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class CallHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //     $call_history = DB::table('call_histories as ch')
    //     ->join('customer_creation_profiles as cc', 'cc.id', 'ch.customer_id')
    //     ->join('call_types_mst as ct', 'ct.id', 'ch.call_type_id')
    //     ->join('business_forecasts as bf', 'bf.id', 'ch.bizz_forecast_id')
    //     ->join('business_forecast_statuses as bfs', 'bfs.id', 'ch.bizz_forecast_status_id')
    //     ->join('users as u', 'u.id', 'ch.executive_id')
    //     ->join('call_procurement_types as pt', 'pt.id', 'ch.procurement_type_id')
    //     ->select(
    //         'ch.id as chid',
    //         'ch.main_id',
    //         'cc.id as ccid',
    //         'cc.customer_name',
    //         'ct.id as ctid',
    //         'ct.name as callname',
    //         'bf.id as bfid',
    //         'bf.name as bizzname',
    //         'bfs.id as bfsid',
    //         'bfs.status_name as bizzstatusname',
    //         'u.id as uid',
    //         'u.name as username',
    //         'pt.id as ptid',
    //         'pt.name as proname',
    //         'ch.call_date',
    //         'ch.action',
    //         'ch.next_followup_date',
    //         'ch.description',
    //         'ch.close_date',
    //         'ch.additional_info',
    //         'ch.remarks',
    //     )
    //     ->get();
    // if ($call_history)
    //     return response()->json([
    //         'status' => 200,
    //         'callhistory' => $call_history
    //     ]);
    // else {
    //     return response()->json([
    //         'status' => 404,
    //         'message' => 'The provided credentials are incorrect.'
    //     ]);
    // }
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
        
        if ($user['userid']) {
            $request->request->add(['created_by' => $user['userid']]);
            $request->request->add(['executive_id' => $user['userid']]);
            $request->request->remove('tokenid');

            if (!empty($request->next_followup_date)) {
                $request->request->add(['action' => 'next_followup']);
            }
            if (!empty($request->close_date)) {
                $request->request->add(['action' => 'close']);
            }

            $call_history_add = CallHistory::firstOrCreate($request->all());
       
            if ($call_history_add) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Next Follow Up Created Successfully!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallHistory  $callHistory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $show_call_history = DB::table('call_histories as ch')
        ->join('call_log_creations as clc', 'clc.id', 'ch.main_id')
        ->join('customer_creation_profiles as cc', 'cc.id', 'ch.customer_id')
        ->join('call_types_mst as ct', 'ct.id', 'ch.call_type_id')
        ->join('business_forecasts as bf', 'bf.id', 'ch.bizz_forecast_id')
        ->leftJoin('business_forecast_statuses as bfs', 'bfs.id', 'ch.bizz_forecast_status_id')
        ->join('users as u', 'u.id', 'ch.executive_id')
        
       // ->join('call_procurement_types as pt', 'pt.id', 'ch.procurement_type_id')
        // ->where('ch.main_id', $id)
        ->select(
            'ch.id as chid',
            'ch.main_id',
            'clc.callid',
            'cc.id as cust_id',
            'cc.customer_name',
            'ct.id as call_id',
            'ct.name as callname',
            'bf.id as bizz_id',
            'bf.name as bizzname',
            'bfs.id as bizz_status_id',
            'bfs.status_name as bizzstatusname',
            //'u.id as uid',
            'u.name as userName',
            //'pt.id as proc_id',
            //'pt.name as proname',
            'ch.procurement_type_id as proc_id',
            'ch.executive_id as user_id',
            'ch.call_date',
            'ch.action',
            'ch.next_followup_date',
            'ch.description',
            'ch.close_date',
            'ch.close_status_id',
            'ch.additional_info',
            'ch.remarks',
        )
        
         ->where('ch.main_id', $id)
         ->latest('ch.id')->first();
        // dd($show_call_history);
       //->get();
       
    if ($show_call_history)
        return response()->json([
            'status' => 200,
            'showcallhistory' => $show_call_history,
            // 'count' => $count,
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
     * @param  \App\Models\CallHistory  $callHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(CallHistory $callHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallHistory  $callHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $user = Token::where('tokenid', $request->tokenid)->first();
        // if ($user['userid']) {
        //     $request->request->remove('tokenid');

        //     if (!empty($request->next_followup_date)) {
        //         $request->request->add(['action' => 'next_followup']);
        //     }
        //     if (!empty($request->close_date)) {
        //         $request->request->add(['action' => 'close']);
        //     }

            
        //     $call_history_update = CallHistory::findOrFail($id)->update($request->all());
        //     if ($call_history_update) {
        //         return response()->json([
        //             'status' => 200,
        //             'message' => 'Call History Updated Successfully!',
        //         ]);
        //     }
        // } else {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Sorry, Failed to Update, Try again later'
        //     ]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallHistory  $callHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function test()
    {
        // $call_history = new CallLog;
        // $call_history->call_date = $call_date;
        // $call_history->action = $request->action;
        // $call_history->next_followup_date = $request->next_followup_date;
        // $call_history->description = $request->description;
        // $call_history->close_date = $request->close_date;
        // $call_history->close_status_id = $request->close_status_id;
        // $call_history->remarks = $request->remarks;
        // $call_history->created_by = $user['userid'];
        // $call_history->save();

    }

    public function getCallHistory($id)
    {
        $call_history = DB::table('call_histories as ch')
            ->join('call_log_creations as clc', 'clc.id', 'ch.main_id')
            ->join('customer_creation_profiles as cc', 'cc.id', 'ch.customer_id')
            ->join('call_types_mst as ct', 'ct.id', 'ch.call_type_id')
            ->join('business_forecasts as bf', 'bf.id', 'ch.bizz_forecast_id')
            ->leftJoin('business_forecast_statuses as bfs', 'bfs.id', 'ch.bizz_forecast_status_id')
            ->join('users as u', 'u.id', 'ch.executive_id')
            // ->join('call_procurement_types as pt', 'pt.id', 'ch.procurement_type_id')
            ->where('ch.main_id', $id)
            ->select(
                'ch.id as chid',
                'ch.main_id',
                'clc.callid',
                'cc.id as ccid',
                'cc.customer_name',
                'ct.id as ctid',
                'ct.name as callname',
                'bf.id as bfid',
                'bf.name as bizzname',
                'bfs.id as bfsid',
                'bfs.status_name as bizzstatusname',
                'u.id as uid',
                'u.name as username',
                // 'pt.id as ptid',
                // 'pt.name as proname',
                'ch.call_date',
                'ch.action',
                'ch.next_followup_date',
                'ch.description',
                'ch.close_date',
                'ch.additional_info',
                'ch.remarks'
            )
            ->get();
    
        if ($call_history) {
            return response()->json([
                'status' => 200,
                'getcallhistory' => $call_history
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    

}
