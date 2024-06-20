<?php

namespace App\Http\Controllers;

use App\Models\CallType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class CallTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $call = CallType::orderBy('created_at', 'desc')->get();

        if ($call)
            return response()->json([
                'status' => 200,
                'calltype' => $call
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
        return "Create Function";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        if($user['userid'])
        {
        $call_type = CallType::where('name', '=', $request->name)->exists();
        if ($call_type) {
            return response()->json([
                'status' => 400,
                'message' => 'Call Type Name Already Exists!'
            ]);
        }

        $request->request->add(['created_by' => $user['userid']]);

        // $validator = Validator::make($request->all(), ['call_type_name' => 'required|string', 'status' => 'required']);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => $validator->messages(),
        //     ]);
        // }
        $request->request->remove('tokenId');
        $call = CallType::firstOrCreate($request->all());
        if ($call) {
            return response()->json([
                'status' => 200,
                'message' => 'Call Type Created Succssfully!'
            ]);
        }
    }
    else{
        return response()->json([
            'status' => 400,
            'message' => 'Provided Credentials are Incorrect!'
        ]);
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallType  $callType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $call = CallType::find($id);
        if ($call)
            return response()->json([
                'status' => 200,
                'calltype' => $call
            ]);
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
     * @param  \App\Models\CallType  $callType
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return "Edit Function";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallType  $callType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        if($user['userid'])
        {
        $call_type = CallType::where('name', '=', $request->name)
        ->where('activeStatus', '=', $request->activeStatus)
        ->where('id', '!=', $id)->exists();
    if ($call_type) {
        return response()->json([
            'status' => 400,
            'message' => 'Call Type Name Already Exists!'
        ]);
        }
        }
    $request->request->add(['updated_by' => $user['userid']]);
    // $validator = Validator::make($request->all(), ['unit_name' => 'required|string', 'unit_status' => 'required']);
    // if ($validator->fails()) {
    //     return response()->json([
    //         'status' => 400,
    //         'message' => $validator->message(),
    //     ]);
    // } 

    $request->request->remove('tokenId');
    
    $call = CallType::findOrFail($id)->update($request->all());

        if ($call)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallType  $callType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $call = CallType::destroy($id);
        if ($call)
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
    public function getCallTypeList()
    {
        $calltypes = CallType::where("activeStatus", "=", "Active")->get();

        $callTypeList= [];
        foreach($calltypes as $calltype){
            $callTypeList[] = ["value" => $calltype['id'], "label" =>  $calltype['name']] ;
        }
        
        return response()->json([
            'calltype' =>  $callTypeList,

        ]);
    }

    public function CallTypeTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
        $header = ['Call Type','Status'];
        $accessor=['name','activeStatus'];
        
        $call = CallType::orderBy('created_at', 'desc')->get();
        if ($call)
            return response()->json([
                'status' => 200,
                'title' => 'CallType',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $call
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
