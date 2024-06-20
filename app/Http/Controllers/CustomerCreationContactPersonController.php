<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreationContactPerson;
use App\Models\Token;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CustomerCreationContactPersonController extends Controller
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
            $CustomerCreation = new CustomerCreationContactPerson;
            $CustomerCreation -> contact_person = $request->contactPersonData['person_name'] ;
            $CustomerCreation -> email = $request->contactPersonData['email'] ;
            $CustomerCreation -> mobile_no = $request->contactPersonData['mobile'] ;
            $CustomerCreation -> designation = $request->contactPersonData['designation'] ;
            $CustomerCreation -> cust_creation_mainid = $request->cust_creation_mainid ;
            $CustomerCreation -> createdby_userid = $userid ;
            $CustomerCreation -> updatedby_userid = 0 ;
           
            // $CustomerCreation -> isCustCreationCompleted = 0;
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
     * @param  \App\Models\CustomerCreationContactPerson  $customerCreationContactPerson
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCreationContactPerson $customerCreationContactPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCreationContactPerson  $customerCreationContactPerson
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCreationContactPerson $customerCreationContactPerson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCreationContactPerson  $customerCreationContactPerson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($user){
            $contact = CustomerCreationContactPerson::findOrFail($id)->update([
                'contact_person' => $request->contactPersonData['person_name'],
                'mobile_no' => $request->contactPersonData['mobile'],
                'designation' => $request->contactPersonData['designation'],
                'email' => $request->contactPersonData['email'],
                'updatedby_userid'=>  $userid 
            ]);
        }

        if ($contact){
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
     * @param  \App\Models\CustomerCreationContactPerson  $customerCreationContactPerson
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $contact = CustomerCreationContactPerson::where('id',$id)->update([
        //     'delete_status' => 1,
        // ]);
        // if ($contact)
        //     return response()->json([
        //         'status' => 200,
        //         'message' => "Deleted Successfully!"
        //     ]);

        try{
            $contact = CustomerCreationContactPerson::destroy($id);
            if($contact)    
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
        $contact = DB::table('customer_creation_contact_people')
        ->where('delete_status', 0)
        ->where('cust_creation_mainid', $request->mainid)
        ->select('*') 
        ->orderBy('id', 'desc')       
        ->get();
    
        if ($contact)
            return response()->json([
                'status' => 200,
                'contact' => $contact
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
