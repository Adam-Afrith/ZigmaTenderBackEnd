<?php

namespace App\Http\Controllers;

use App\Models\DayWiseReport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;


class DayWiseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DayWiseReport  $dayWiseReport
     * @return \Illuminate\Http\Response
     */
    public function show(DayWiseReport $dayWiseReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DayWiseReport  $dayWiseReport
     * @return \Illuminate\Http\Response
     */
    public function edit(DayWiseReport $dayWiseReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DayWiseReport  $dayWiseReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DayWiseReport $dayWiseReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DayWiseReport  $dayWiseReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(DayWiseReport $dayWiseReport)
    {
        //
    }

    public function getDayWiseReport(Request $request)
    {
       
         $from_date = $request->from_date;
         $to_date = $request->to_date;
         $countryID = $request->country_id;
         $stateID = $request->state_id;
         $districtID = $request->district_id;
         $customerID = $request->customer_id;
         $executiveID = $request->executive_id;

        $daywisereport = DB::table('call_log_creations as clc')
                        ->join('customer_creation_profiles as ccp','ccp.id','clc.customer_id')
                        ->join('users as u','u.id','clc.executive_id')
                        ->select(
                            'clc.id',
                            'clc.callid',
                            'clc.customer_id',
                            'ccp.customer_name',
                            'ccp.country',
                            'ccp.state',
                            'ccp.district',
                            'u.userType',
                            'u.userName',
                            'clc.executive_id',
                            'clc.action',
                            'clc.call_date',
                            'clc.close_date',
                            'clc.next_followup_date',
                        );
                       
                       if($from_date && $to_date) {
                     
                            $from_date_formatted = date('Y-m-d 00:00:00', strtotime($from_date));
                            $to_date_formatted = date('Y-m-d 23:59:59', strtotime($to_date));
                            $daywisereport->whereBetween('clc.call_date', [$from_date_formatted, $to_date_formatted]);
                        } 
                        elseif($from_date) {
                        
                           $daywisereport->whereDate('clc.call_date', $from_date);
                          
                        } 
                        elseif($to_date) {
                            
                            $daywisereport->whereDate('clc.call_date', $to_date);
                          
                        }
                        if($countryID)
                        {
                          
                            $daywisereport->where('ccp.country', $countryID);
                        }
                        if($stateID)
                        {
                       
                            $daywisereport->where('ccp.state', $stateID);
                        }
                        if($districtID)
                        {
                            
                            $daywisereport->where('ccp.district', $districtID);
                        }
                        if($customerID)
                        {
                          
                            $daywisereport->where('clc.customer_id', $customerID);
                        }
                        if($executiveID)
                        {
                           
                            $daywisereport->where('clc.executive_id', $executiveID);
                        }

            
                        $daywisereport = $daywisereport->get();

                    //     $query = str_replace(array('?'), array('\'%s\''), $daywisereport->toSql());
                    //     $query = vsprintf($query, $daywisereport->getBindings());
                    //    return $query;

                     
                        $count = count($daywisereport);
                        if ($daywisereport)
                        return response()->json([
                            'status' => 200,
                            'daywisereport' => $daywisereport,
                            'count' => $count,
                        ]);
                        else {
                            return response()->json([
                                'status' => 404,
                                'message' => 'The provided credentials are incorrect.'
                            ]);
                        }


    }


public function CallReportTable(Request $request)
{
    $user = Token::where("tokenid", $request->tokenid)->first();
    if($user['userid'])
    {
        $header = ['Call No','Customer Name','Status','Next Follow Up Date','Started','Finished'];
        $accessor = ['callid','customer_name','status','next_followup_date','call_date','close_date'];

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $countryID = $request->country_id;
        $stateID = $request->state_id;
        $districtID = $request->district_id;
        $customerID = $request->customer_id;
        $executiveID = $request->executive_id;

       $daywisereport = DB::table('call_log_creations as clc')
                       ->join('customer_creation_profiles as ccp','ccp.id','clc.customer_id')
                       ->join('users as u','u.id','clc.executive_id')
                       ->select(
                           'clc.id',
                           'clc.callid',
                           'clc.customer_id',
                           'ccp.customer_name',
                           'ccp.country',
                           'ccp.state',
                           'ccp.district',
                           'u.userType',
                           'u.userName',
                           'clc.executive_id',
                           'clc.action as status',
                           'clc.call_date',
                           'clc.close_date',
                           'clc.next_followup_date',
                       );
                      
                      if($from_date && $to_date) {
                    
                           $from_date_formatted = date('Y-m-d 00:00:00', strtotime($from_date));
                           $to_date_formatted = date('Y-m-d 23:59:59', strtotime($to_date));
                           $daywisereport->whereBetween('clc.call_date', [$from_date_formatted, $to_date_formatted]);
                       } 
                       elseif($from_date) {
                       
                          $daywisereport->whereDate('clc.call_date', $from_date);
                         
                       } 
                       elseif($to_date) {
                           
                           $daywisereport->whereDate('clc.call_date', $to_date);
                         
                       }
                       if($countryID)
                       {
                         
                           $daywisereport->where('ccp.country', $countryID);
                       }
                       if($stateID)
                       {
                      
                           $daywisereport->where('ccp.state', $stateID);
                       }
                       if($districtID)
                       {
                           
                           $daywisereport->where('ccp.district', $districtID);
                       }
                       if($customerID)
                       {
                         
                           $daywisereport->where('clc.customer_id', $customerID);
                       }
                       if($executiveID)
                       {
                          
                           $daywisereport->where('clc.executive_id', $executiveID);
                       }

           
                       $daywisereport = $daywisereport->get();

                   //     $query = str_replace(array('?'), array('\'%s\''), $daywisereport->toSql());
                   //     $query = vsprintf($query, $daywisereport->getBindings());
                   //    return $query;

                    
                       

                       if ($daywisereport)
                       return response()->json([
                           'status' => 200,
                           'title' => 'CallReport',
                           'header' => $header,
                           'accessor' => $accessor,
                           'data' => $daywisereport,
                           
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
