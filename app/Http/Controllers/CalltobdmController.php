<?php

namespace App\Http\Controllers;

use App\Models\calltobdm;
use App\Models\calltobdm_has_customer;
use App\Models\CustomerCreationProfile;
use App\Models\User;
use App\Models\Token;
use App\Models\CallAssignBDM;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CalltobdmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $callToBdmList = calltobdm::with('user', 'customer.customer')->get();

        if ($callToBdmList)
        return response()->json([
            'status' => 200,
            'callToBdmList' => $callToBdmList
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         //get the user id
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $calltobdm = new calltobdm;
            $calltobdm->user_id = $request->staffName;
            $calltobdm->created_userid  =   $userid;
            $calltobdm->edited_userid   =   null;
            $calltobdm->save();
        } 

        if ($calltobdm) {

          
            foreach($request->customer as $customer){
                $cus = new calltobdm_has_customer;
                $cus->calltobdm_id =  $calltobdm['id'];
                $cus->customer_id =  $customer['value'];
                $cus->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'New Call to BDM Assigned Successfully',
                'id' => $calltobdm['id'],
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
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $calltobdm = calltobdm::find($id);
      
        if ($calltobdm){

            $user = User::find($calltobdm['user_id']);
            $customers = calltobdm_has_customer::where('calltobdm_id', $calltobdm['id'])->get();

            $calltobdm['user_id'] =  [
                'value' => $user['id'],
                'label' =>  $user['name'],
            ];

            $arrCollection = [];
            foreach( $customers as $customer){
                $customerProfile = CustomerCreationProfile::find($customer['customer_id']);

                $arrCollection[] = [
                    'value' => $customerProfile['id'],
                    'label' =>  $customerProfile['customer_name'],
                ];
            }

            $calltobdm['customer_id'] =  $arrCollection;

            return response()->json([
                'status' => 200,
                'calltobdm' => $calltobdm,
            ]);
        }
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
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function edit(calltobdm $calltobdm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $calltobdm               = calltobdm::find($id);
            $calltobdm->user_id      = $request->staffName;
            // $calltobdm->created_userid  =   $userid;
            $calltobdm->edited_userid   = $userid;
            $calltobdm->save();
        } 

        if ($calltobdm) {

            $delete = calltobdm_has_customer::where('calltobdm_id', $calltobdm["id"])->delete();

            foreach($request->customer as $customer){

                $cus = new calltobdm_has_customer;
                $cus->calltobdm_id =  $calltobdm['id'];
                $cus->customer_id =  $customer['value'];
                $cus->save();

            }


            return response()->json([
                'status'    => 200,
                'message'   => 'New Call to BDM Updated Successfully',
            ]);
        }else{
            return response()->json([
                'status'    => 400,
                'message'   => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\calltobdm  $calltobdm
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $calltobdm = calltobdm::destroy($id);
            if($calltobdm)    
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

    // public function updateAssignedCustomer(Request $request){
    //     // return $request;

    //      //get the user id
    //      $user = Token::where('tokenid', $request->tokenid)->first();
    //      $userid = $user['userid'];
    //     $id="";
    //      if($userid){
    //         //checking the existence of bdm id in calltobdm table
    //          $bdmExist = calltobdm::where('user_id',$request->bdm_id)->first();

    //         //if bdm id not in table have to create new record in both main and sublist
    //         if(empty($bdmExist))
    //         {
    //             // return "Empty ";
    //                 $calltobdm = new calltobdm;
    //                 $calltobdm->user_id = $request->bdm_id;
    //                 $calltobdm->created_userid  =   $userid;
    //                 $calltobdm->edited_userid   =   null;
    //                 $calltobdm->save();
    //                 $id=$calltobdm->id;
    //         }
    //         else{ //if bdm id in table have to update the sublist alone
                
    //                 $bdmExist->user_id = $request->bdm_id;
    //                 // $bdmExist->created_userid  =   $userid;
    //                 $bdmExist->edited_userid   =   $userid;
    //                 $bdmExist->save();
    //                 $id =$bdmExist->id;
    //             // return "Not Empty";
    //         }
    //         // return "Test ".$calltobdm;
    //         }



    // //          $calltobdm->user_id = $request->bdm_id;
    // //          $calltobdm->created_userid  =   $userid;
    // //          $calltobdm->edited_userid   =   null;
    // //          $calltobdm->save();
    // //      } 
    //         echo "id --- $id";
    //      if ($id) {
    //          foreach($request->input as $key=>$value){
    //              $cus = calltobdm_has_customer::where('calltobdm_id',$id)->get();
    //                 //if value 1 then, have to update id exists/insert new record
    //                 if($value === 1)
    //                 {
    //                     $cus = calltobdm_has_customer::where('calltobdm_id',$id)->where('customer_id',$key)->get();
    //                     if($cus )


    //                 }//if value 0 then, have to update customer id if exists
    //                 else{
    //                     // $cus->calltobdm_id =  $id;
    //                     // $cus->customer_id =  $customer['value'];
    //                     // $cus->save();
    //                 }
    //             //  $cus->calltobdm_id =  $id;
    //             //  $cus->customer_id =  $customer['value'];
    //             //  $cus->save();
    //          }
 
    //          return response()->json([
    //              'status' => 200,
    //              'message' => 'New Call to BDM Assigned Successfully',
    //             //  'id' => $calltobdm['id'],
    //          ]);
    //      }else{
    //          return response()->json([
    //              'status' => 400,
    //              'message' => 'Unable to save!'
    //          ]);
    //      }
    // }


    public function updateAssignedCustomer(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        $request->request->remove('tokenid');

        if($user['userid'])
        {  
            foreach($request->input as $key => $value)
            {
                $rowData = CallAssignBDM::where('customer_id', $key)->first();
                // where('bdm_id', $request->bdm_id)
                
                //if $rowData has value, then update it, or create a new record
                if(empty($rowData) )
                {
                    if($value == 1)
                    {
                        $createNew=new CallAssignBDM;
                        $createNew->bdm_id= $request->bdm_id;
                        $createNew->customer_id= $key;
                        $createNew->assign_status= (string)$value;
                        $createNew->created_userid=$user['userid'];
                        $createNew->save();
                    }
                }
                else{
                        if($rowData->assign_status != $value)
                        {
                        $updateCurrent=CallAssignBDM::find($rowData->id);
                        $updateCurrent->bdm_id= $request->bdm_id;
                        $updateCurrent->assign_status= (string)$value;
                        $updateCurrent->edited_userid=$user['userid'];
                        $updateCurrent->save();
                        }
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Assigned Customers to BDM!'
            ]);

        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
        }
    }
}
