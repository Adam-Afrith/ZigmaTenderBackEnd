<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreationBankDetails;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCreationBankDetailsController extends Controller
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
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($user){
            $CustomerCreation = new CustomerCreationBankDetails;
            $CustomerCreation -> ifsccode = $request->bankdetails['ifsccode'];
            $CustomerCreation -> bankname = $request->bankdetails['bankname'];
            $CustomerCreation -> bankaddress = $request->bankdetails['bankaddress'];
            $CustomerCreation -> beneficiaryaccountname = $request->bankdetails['beneficiaryaccountname'];
            $CustomerCreation -> accountnumber = $request->bankdetails['accountnumber'];
            $CustomerCreation -> cust_creation_mainid = $request->cust_creation_mainid;
            $CustomerCreation -> createdby_userid = $userid ;
            $CustomerCreation -> updatedby_userid = 0 ;
            $CustomerCreation->save();
        }

        if ($CustomerCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Added!'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCreationBankDetails  $customerCreationBankDetails
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCreationBankDetails $customerCreationBankDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCreationBankDetails  $customerCreationBankDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCreationBankDetails $customerCreationBankDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCreationBankDetails  $customerCreationBankDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($user){
            $bankdetails = CustomerCreationBankDetails::findOrFail($id)->update([
                'ifsccode' => $request->bankdetails['ifsccode'],
                'bankname' => $request->bankdetails['bankname'],
                'bankaddress' => $request->bankdetails['bankaddress'],
                'beneficiaryaccountname' => $request->bankdetails['beneficiaryaccountname'],
                'accountnumber' => $request->bankdetails['accountnumber'],
                'updatedby_userid'=>  $userid 
            ]);
        }
        
        if ($bankdetails){
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => "Unable to update!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCreationBankDetails  $customerCreationBankDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $bankdetails = CustomerCreationBankDetails::destroy($id);
            if($bankdetails)    
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
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    public function getlist(Request $request){
        $bankdetails = DB::table('customer_creation_bank_details')
        ->where('cust_creation_mainid', $request->mainid)
        ->select('*') 
        ->orderBy('id', 'desc')       
        ->get();
    
        if ($bankdetails)
            return response()->json([
                'status' => 200,
                'bankdetails' => $bankdetails
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
