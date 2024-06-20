<?php

namespace App\Http\Controllers;

use App\Models\BusinessForecastStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessForecast;
use App\Models\Token;
class BusinessForecastStatusController extends Controller
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
        //status_name: statusInput.statusname,
      
        $token_id =$request->token;

        $user = Token::where("tokenid", $token_id)->first();
        if($user['userid'])
        {


$user_id =$user['userid'];
$status_name=$request->status_name;
$forecast=$request->forecast;
$active_satus=$request->forecast_status;




$BusinessForecastStatus = new BusinessForecastStatus();
$BusinessForecastStatus->status_name = $status_name;
$BusinessForecastStatus->bizz_forecast_id = $forecast;
$BusinessForecastStatus->active_status = $active_satus;
$BusinessForecastStatus->created_userid = $user_id;
$BusinessForecastStatus->save();
if($BusinessForecastStatus){

    return response()->json([
        'status' => 200,
        'msg' => 'Sucessfuly Added',
    ]);
}else{

    return response()->json([
        'status' => 300,
        'msg' => 'something Went Wrong',
    ]);
}
        
        
        }
        else{



            return response()->json([
                'status' => 500,
                'msg' => 'Athuntication Fails',
            ]);
        }



       

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessForecastStatus  $businessForecastStatus
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $token_id =$request->tokenid;

     
    $user = Token::where("tokenid", $token_id)->first();

    if($user['userid'])
    {
            $getList = BusinessForecastStatus::leftJoin('business_forecasts as b', 'business_forecast_statuses.bizz_forecast_id', '=', 'b.id')
            ->select('business_forecast_statuses.*', 'b.name')
            ->orderBy('business_forecast_statuses.id')
            ->get();

            if($getList){
                return response()->json([
                    'status' => 200,
                    'getList' => $getList,
                ]);

            }else{
                return response()->json([
                    'status' => 300,
                    'msg' => 'Data Not Found',
                ]);
            }
        }
        else{

            return response()->json([
                'status' => 500,
                'msg' => 'Athuntication Fails',
            ]);
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessForecastStatus  $businessForecastStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $token_id=$request->token;

        $user = Token::where("tokenid", $token_id)->first();
        if($user['userid'])
        {

        $editid=$request->editid;


        $getedit=BusinessForecastStatus::where('id', $editid)
        ->select('*')
        ->first();
        if($getedit){

            return response()->json([
                'status' => 200,
                'getedit' => $getedit,
            ]);

        }else{

            return response()->json([
                'status' => 200,
                'msg' => 'data not found',
            ]);

        }

        }else{


            return response()->json([
                'status' => 500,
                'msg' => 'Athuntication Fails',
            ]);


        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BusinessForecastStatus  $businessForecastStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusinessForecastStatus $businessForecastStatus)
    {
        //



        $token_id =$request->token;

        $user = Token::where("tokenid", $token_id)->first();
        if($user['userid'])
        {

            $user_id =$user['userid'];
            $status_name=$request->status_name;
            $forecast=$request->forecast;
            $active_satus=$request->forecast_status;
            $id=$request->updateId;
            
            
            
            $BusinessForecastStatus = BusinessForecastStatus::find($id);
            $BusinessForecastStatus->status_name = $status_name;
            $BusinessForecastStatus->bizz_forecast_id = $forecast;
            $BusinessForecastStatus->active_status = $active_satus;
            $BusinessForecastStatus->created_userid = $user_id;
            $BusinessForecastStatus->save();
if($BusinessForecastStatus){

    return response()->json([
        'status' => 200,
        'msg' => 'Sucessfuly Updated',
    ]);


}else{

    return response()->json([
        'status' => 300,
        'msg' => 'Not Updated',
    ]);

}



        }else{



            return response()->json([
                'status' => 500,
                'msg' => 'Athuntication Fails',
            ]);

        }









       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessForecastStatus  $businessForecastStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $token_id =$request->token;

        $user = Token::where("tokenid", $token_id)->first();
        if($user['userid'])
        {
            $id = $request->del_id; // ID of the record to delete

            $BusinessForecastStatus = BusinessForecastStatus::find($id);
            if ($BusinessForecastStatus) {
                $BusinessForecastStatus->delete();
            
                // Record successfully deleted
                return response()->json([
                    'status' => 200,
                    'message' => 'Record deleted successfully.',
                ]);
            } else {
                // Record not found
                return response()->json([
                    'status' => 404,
                    'message' => 'Record not found.',
                ]);
            }


        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Athucantication Fail.'
            ]);

        }


    }


    public function ForecastList(Request  $request)
{

    $token_id =$request->token;

    $user = Token::where("tokenid", $token_id)->first();
    if($user['userid'])
    {
    
        $getforecaste=BusinessForecast::where('activeStatus','Active')->get();
    if($getforecaste)
    {
        return response()->json([
            'status' => 200,
            'get_forecast' => $getforecaste,
        ]);
    
    }else{
        return response()->json([
            'status' => 400,
            'msg' =>'Forecast Not Found',
        ]);
    
    }
    
    }else{
    
        return response()->json([
            'status' => 500,
            'message' => 'Athucantication Fail.'
        ]);
    }

}


   

   


}