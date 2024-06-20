<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreationMain;
use App\Models\Token;
use Illuminate\Http\Request;

class CustomerCreationMainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customercreationList = CustomerCreationMain::where([
            ['delete_status',0],
            ['isCustCreationProcessCompleted',1]
        ])->get();

        return response()->json([
            'customercreationList' =>   $customercreationList
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
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $customercreation  = null;
        $createCustomer = null;
        if($user){
            $userid = $user['userid'];
            $customercreation = $CustomerCreationMain::where([
            ['user_id', $userid ],
            ['isCustCreationProcessCompleted',0],
            ['delete_status',0]
           ])->orderBy('id', 'desc')->first();

           if(!$customercreation){
            $createCustomer = new CustomerCreationMain;
            $createCustomer -> user_id = $userid;
            $createCustomer -> isCustCreationProcessCompleted = 0;
            $createCustomer -> delete_status = 0;
            $createCustomer -> save();
           }
        }

        return response()->json([
            'customercreation' =>   $createCustomer
        ]);
    }

    /**
     * Display the specified resource.
      *
     * @param  \App\Models\CustomerCreationMain  $customerCreationMain
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCreationMain $customerCreationMain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCreationMain  $customerCreationMain
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCreationMain $customerCreationMain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCreationMain  $customerCreationMain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCreationMain $customerCreationMain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCreationMain  $customerCreationMain
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCreationMain $customerCreationMain)
    {
        //
    }

    public function getMainid(Request $request){
        $user = Token::where('tokenid', $request->tokenid)->first();
        $customercreation  = null ;

        if($user){
            $userid = $user['userid'];
            $customercreation = CustomerCreationMain::where([
            ['user_id', $userid ],
            ['isCustCrea tionProcessCompleted',0],
            ['delete_status',0]
           ])->orderBy('id', 'desc')->first();
        }

        return response()->json([
            'customercreation' =>   $customercreation
        ]);

    }
}
