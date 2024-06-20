<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreationProfile;
use App\Models\CustomerCreationMain;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\User;

class CustomerCreationProfileController extends Controller
{
    public function index()
    {
        //
        $customercreationList = DB::table('customer_creation_profiles')
            ->join('country_masters', 'country_masters.id', 'customer_creation_profiles.country')
            ->join('state_masters', 'state_masters.id', 'customer_creation_profiles.state')
            ->join('district_masters', 'district_masters.id', 'customer_creation_profiles.district')
            ->join('city_masters', 'city_masters.id', 'customer_creation_profiles.city')
            ->where([
                'customer_creation_profiles.delete_status' => 0,
            ])
            ->select(
                'customer_creation_profiles.id',
                'customer_creation_profiles.customer_name',
                'country_masters.country_name',
                'state_masters.state_name',
                'city_masters.city_name',
                'customer_creation_profiles.smart_city'
            )
            ->get();

        return response()->json([
            'customercreationList' =>   $customercreationList
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //get the user id
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        // $CustomerCreation = CustomerCreationProfile::firstOrCreate($request->profileData);
        if ($userid) {

            $CustomerCreation = new CustomerCreationProfile;
            // $CustomerCreation -> customer_no = $request->profileData['customer_no'] ;
            $customerno = $this->getCustNo1($request->profileData['state'], $request->profileData['smart_city']);
            $CustomerCreation->customer_no =    $customerno;
            $CustomerCreation->customer_category = $request->profileData['customer_category'];
            $CustomerCreation->customer_name = $request->profileData['customer_name'];
            $CustomerCreation->smart_city = $request->profileData['smart_city'];
            $CustomerCreation->customer_sub_category = $request->profileData['customer_sub_category'];
            $CustomerCreation->country = $request->profileData['country'];
            $CustomerCreation->state = $request->profileData['state'];
            $CustomerCreation->district = $request->profileData['district'];
            $CustomerCreation->city = $request->profileData['city'];
            $CustomerCreation->pincode = $request->profileData['pincode'];
            $CustomerCreation->address = $request->profileData['address'];
            $CustomerCreation->phone = $request->profileData['phone'];
            $CustomerCreation->pan = $request->profileData['pan'];
            $CustomerCreation->mobile_no = $request->profileData['mobile_no'];
            $CustomerCreation->email = $request->profileData['email'];
            $CustomerCreation->gst_registered = $request->profileData['gst_registered'];
            $CustomerCreation->gst_no = $request->profileData['gst_no'];
            $CustomerCreation->website = $request->profileData['website'];
            $CustomerCreation->createdby_userid = $userid;
            $CustomerCreation->updatedby_userid = 0;
            $CustomerCreation->save();
        }

        if ($CustomerCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Customer Profile Has created Succssfully!',
                'id' => $CustomerCreation['id'],
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $CustomerCreation = CustomerCreationProfile::find($id);
        if ($CustomerCreation)
            return response()->json([
                'status' => 200,
                'profiledata' => $CustomerCreation
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
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCreationProfile $customerCreationProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($userid) {
            $updatedata = $request->profileData;
            $updatedata['updatedby_userid'] = $userid;

            $savedData = CustomerCreationProfile::find($id);
            if ($savedData['state'] !=  $updatedata['state']) {
                $updatedata['customer_no']  = $this->getCustNo1($updatedata['state'], $updatedata['smart_city']);
            }

            $CustomerCreation = CustomerCreationProfile::findOrFail($id)->update($updatedata);
        }


        if ($CustomerCreation)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCreationProfile  $customerCreationProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $deleteCustomer = CustomerCreationProfile::where('id',$id)->update([
        //     'delete_status' => 1,
        // ]);
        // if ($deleteCustomer)
        //     return response()->json([
        //         'status' => 200,
        //         'message' => "Deleted Successfully!"
        //     ]);

        try {
            $deleteCustomer = CustomerCreationProfile::destroy($id);
            if ($deleteCustomer) {
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

    public function getProfileFromData(Request $request)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();
        $profileFormData = null;
        if ($user) {
            $userid = $user['userid'];

            $userid = $user['userid'];
            $customercreation = CustomerCreationMain::where([
                ['user_id', $userid],
                ['isCustCreationProcessCompleted', 0],
                ['delete_status', 0]
            ])->orderBy('id', 'desc')->first();

            $profileFormData = CustomerCreationProfile::where([
                ['userid', $userid],
                ['cust_creation_mainid', $customercreation['id']],
                // ['isProfileFormCompleted',1],
                // ['isCustCreationCompleted',0],
                ['delete_status', 0]
            ])->orderBy('id', 'desc')->first();
        }

        return response()->json([
            'profileFormData' =>  $profileFormData,
            'customercreation' => $customercreation
        ]);
    }


    public function getFormNo()
    {
        $profileFormData = CustomerCreationProfile::where([
            ['isProfileFormCompleted', 1],
            ['delete_status', 0]
        ])->orderBy('id', 'desc')->first();

        if ($profileFormData) {
            $form_no = $profileFormData['form_no'];
        } else {
            $form_no = 0;
        }

        return response()->json([
            'form_no' => ($form_no + 1)
        ]);
    }

    public function getCustNo($stateid)
    {
        $no =  $this->generateNo($stateid);
        return response()->json([
            'no'  => $no,
        ]);
    }

    public function generateNo($stateid)
    {
        $custno = CustomerCreationProfile::where([
            ['state', $stateid],
            ['delete_status', 0]
        ])->orderBy('id', 'desc')->first();

        if ($custno) {
            $lastNo = $custno['customer_no'];
            $lastNoarr = explode("-", $lastNo);
            $no = $lastNoarr[2] + 1;
        } else {
            $no = 1;
        }
        return sprintf('%02d', $no);
    }

    public function getCustNo1($stateid, $smartcity)
    {
        $no = $this->generateNo($stateid);
        $state = StateMaster::where('id', $stateid)->get();
        if ($state) {
            $statecode = $state[0]['state_code'];

            if ($smartcity == "yes") {
                $SC = "SC";
            }
            if ($smartcity == "no") {
                $SC = "NC";
            }

            return strtoupper($statecode . "-" . $SC . "-" . $no);
            //    return ( $state );

        }
    }

    public function getUlbs($savedulb)
    {
        $ulbs = CustomerCreationProfile::orderBy('id', 'desc')
            ->get();

        $ulbList = [];
        foreach ($ulbs as $ulb) {
            $ulbList[] = ["value" => $ulb['id'], "label" =>  $ulb['customer_name']];
        }
        return  response()->json([
            'ulbList' =>  $ulbList,

        ]);
    }

    //getList() - Used in call creation form - Don't use it form other forms 
    public function getList(Request $request)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();

        if ($user['userid']) {
            $countrys = DB::table('bdm_has_customers as c')
                ->join('customer_creation_profiles as a', 'c.customer_id', 'a.id')
                ->where("c.bdm_id", $user['userid'])
                ->where("c.assign_status", '1')
                ->get();

            $customerList = [];
            foreach ($countrys as $country) {
                $customerList[] = ["value" => $country->customer_id, "label" =>  $country->customer_name];
            }
            return  response()->json([
                'customerList' =>  $customerList,
                // 'country' => $countrys

            ]);
        }
    }

    public function getOptions()
    {

        $customers = CustomerCreationProfile::where("customer_name", "!=", "")->get();

        $customerList = [];
        foreach ($customers as $customer) {
            $customerList[] = ['value' => $customer['id'], 'label' => $customer['customer_name']];
        }

        return  response()->json([
            'customerList' =>  $customerList,
        ]);
    }


    //getFilteredCustomerList - Used to get the customer list based on country, state, district filter input
    //it may have any one or all of country, state, district value
    //it should return customer id, name, already assigned or not
    public function getFilteredCustomerList(Request $request)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();
        if ($user['userid']) {
            $userID = $request->bdm_id;

            if(empty($request->country) && empty($request->state) && empty($request->district))
            {
                
                $customers = DB::table('customer_creation_profiles as a')
                ->where('b.bdm_id', $userID)
                ->where('b.assign_status', '1')
                ->select(
                    'a.id',
                    'a.customer_name',
                    'b.id as rowid',
                    'b.customer_id',
                    'b.bdm_id',
                    'a.country',
                    'c.country_name',
                    'a.state',
                    's.state_name',
                    'd.district_name',
                    'a.district',
                    'a.city',
                    'ct.city_name',
                    'a.mobile_no',
                    'b.assign_status'
                )
                ->join('bdm_has_customers as b', 'a.id', 'b.customer_id')
               
                ->join('country_masters as c','a.country','c.id')
                ->join('state_masters as s','a.state','s.id')
                ->join('district_masters as d','a.district','d.id')
                ->join('city_masters as ct','a.city','ct.id');

                // $query = str_replace(array('?'), array('\'%s\''), $customers->toSql());
                // $query = vsprintf($query, $customers->getBindings());
                // echo $query; 
               $results = $customers->get();
               $resultsSelected = [];
            }
            else{
                //get Assigned Customers of all location

                $customersSelected = DB::table('customer_creation_profiles as a')
                ->where('b.bdm_id', $userID)
                ->where('b.assign_status', '1')
                ->select(
                    'a.id',
                    'a.customer_name',
                    'b.id as rowid',
                    'b.customer_id',
                    'b.bdm_id',
                    'a.country',
                    'c.country_name',
                    'a.state',
                    's.state_name',
                    'd.district_name',
                    'a.district',
                    'a.city',
                    'ct.city_name',
                    'a.mobile_no',
                    'b.assign_status'
                )
                ->join('bdm_has_customers as b', 'a.id', 'b.customer_id')
               
                ->join('country_masters as c','a.country','c.id')
                ->join('state_masters as s','a.state','s.id')
                ->join('district_masters as d','a.district','d.id')
                ->join('city_masters as ct','a.city','ct.id');

               $resultsSelected = $customersSelected->get();



                $customers = DB::table('customer_creation_profiles as a')
                ->select(
                    'a.id',
                    'a.customer_name',
                    'b.id as rowid',
                    'b.customer_id',
                    'b.bdm_id',
                    'a.country',
                    'c.country_name',
                    'a.state',
                    's.state_name',
                    'd.district_name',
                    'a.district',
                    'a.city',
                    'ct.city_name',
                    'a.mobile_no',
                    'b.assign_status'
                )
                ->leftjoin('bdm_has_customers as b', 'a.id', 'b.customer_id')
    
                ->whereNotIn('a.id', function ($query) use ($userID) {
                    $query->select('customer_id')
                        ->from('bdm_has_customers')->where('assign_status', '1');
                })
                
                
                ->join('country_masters as c','a.country','c.id')
                ->join('state_masters as s','a.state','s.id')
                ->join('district_masters as d','a.district','d.id')
                ->join('city_masters as ct','a.city','ct.id');


                if(!empty($request->country))
                {
                $customers->where('a.country',$request->country);
                }
                if(!empty($request->state))
                {
                    $customers->where('a.state',$request->state);
                }
                if(!empty($request->district))
                {
                    $customers->where('a.district', $request->district);
                }

                
                // $query = str_replace(array('?'), array('\'%s\''), $customers->toSql());
                // $query = vsprintf($query, $customers->getBindings());
                // echo $query; 
               $results = $customers->get();

            }

        //Craeted initaly
            // $customers = DB::table('customer_creation_profiles as a')
            //     ->select(
            //         'a.id',
            //         'a.customer_name',
            //         'b.id as rowid',
            //         'b.customer_id',
            //         'b.bdm_id',
            //         'a.country',
            //         'c.country_name',
            //         'a.state',
            //         's.state_name',
            //         'd.district_name',
            //         'a.district',
            //         'a.city',
            //         'ct.city_name',
            //         'a.mobile_no',
            //         'b.assign_status'
            //     )
            //     ->leftjoin('bdm_has_customers as b', 'a.id', 'b.customer_id')
            //     ->where('b.bdm_id', $userID)
            //     ->orWhereNotIn('a.id', function ($query) use ($userID) {
            //         $query->select('customer_id')
            //             ->from('bdm_has_customers')->where('bdm_id', '!=', $userID)->where('assign_status', '0');
            //     })
                
            //     // ->when(`'b.bdm_id' != $userID  && 'assign_status'=1`, function ($query) use ($request) {
            //     //     return $query->where('role', $request->role);
            //     // })
            //     ->join('country_masters as c','a.country','c.id')
            //     ->join('state_masters as s','a.state','s.id')
            //     ->join('district_masters as d','a.district','d.id')
            //     ->join('city_masters as ct','a.city','ct.id');


            //     if(!empty($request->country))
            //     {
            //         $country = $request->country;
            //         $customers->where('a.country', $request->country)
            //         ->orWhereIn('a.country', function ($query) use ($country) {
            //             $query->select('country')
            //                 ->from('customer_creation_profiles')->where('country', $country);
            //         });
            //     }
            //     if(!empty($request->state))
            //     {
            //         $customers->where('a.state',$request->state);
            //     }
            //     if(!empty($request->district))
            //     {
            //         $customers->where('a.district', $request->district);
            //     }


            //     // $query = str_replace(array('?'), array('\'%s\''), $customers->toSql());
            //     // $query = vsprintf($query, $customers->getBindings());
            //     // echo $query; 
            //    $results = $customers->get();



//2nd attenmpt
            // $results = DB::table('customer_creation_profiles as a')
            //     ->leftJoin('bdm_has_customers as b', 'a.id', '=', 'b.customer_id')
            //     ->select(
            //         'a.id',
            //         'a.customer_name',
            //         'b.id as rowid',
            //         'b.customer_id',
            //         'b.bdm_id',
            //         'a.country',
            //         'c.country_name',
            //         'a.state',
            //         's.state_name',
            //         'd.district_name',
            //         'a.district',
            //         'a.city',
            //         'ct.city_name',
            //         'a.mobile_no',
            //         'a.city',
            //         'b.assign_status'
            //     )
            //     ->where(function ($query) use ($userID) {
            //         $query->where('b.bdm_id',  $userID)
            //             ->orWhereNull('b.bdm_id');
            //     })

            //     ->where(function($query) use ($userID) {
            //         $query->where('b.bdm_id', '!=', $userID)
            //               ->where(function($query) {
            //                   $query->whereNull('b.assign_status')
            //                         ->orWhere('b.assign_status', '!=', 1);
            //               });
            //     });
                

            //     if(!empty($request->country))
            //     {
            //         $results->where('a.country', $request->country);
            //     }
            //     if(!empty($request->state))
            //     {
            //         $results->where('a.state',$request->state);
            //     }
            //     if(!empty($request->district))
            //     {
            //         $results->where('a.district', $request->district);
            //     }
            //     $results->join('country_masters as c', 'a.country', 'c.id')
            //     ->join('state_masters as s', 'a.state', 's.id')
            //     ->join('district_masters as d', 'a.district', 'd.id')
            //     ->join('city_masters as ct','a.city','ct.id');

            //     $query = str_replace(array('?'), array('\'%s\''), $results->toSql());
            //     $query = vsprintf($query, $results->getBindings());
            //     return $query; 
            //     $results=$results->get();

        if(count($resultsSelected)>0){
          $list =  array_merge(json_decode($resultsSelected),json_decode($results));
        }
        else{
            $list =$results;
        }

        if($results){
            return  response()->json([
                'customerList' =>   $list,
                'result' =>  $results,
                'selectedList' => $resultsSelected,
            ], 200);
        } else {
            return  response()->json([
                'error' =>  "You are not authorized User",
            ], 400);
        }
    }
}

public function AssignCallsTable(Request $request)
{
    $user = Token::where('tokenid', $request->tokenid)->first();
    if ($user['userid']) {

        $header = ['Name','Country','State','District','Assign Status'];
        $accessor = ['customer_name','country_name','state_name','district_name','assign_status'];

        $userID = $request->bdm_id;

        if(empty($request->country) && empty($request->state) && empty($request->district))
        {
            
            $customers = DB::table('customer_creation_profiles as a')
            ->where('b.bdm_id', $userID)
            ->where('b.assign_status', '1')
            ->select(
                'a.id',
                'a.customer_name',
                'b.id as rowid',
                'b.customer_id',
                'b.bdm_id',
                'a.country',
                'c.country_name',
                'a.state',
                's.state_name',
                'd.district_name',
                'a.district',
                'a.city',
                'ct.city_name',
                'a.mobile_no',
                'b.assign_status'
            )
            ->join('bdm_has_customers as b', 'a.id', 'b.customer_id')
           
            ->join('country_masters as c','a.country','c.id')
            ->join('state_masters as s','a.state','s.id')
            ->join('district_masters as d','a.district','d.id')
            ->join('city_masters as ct','a.city','ct.id');

            // $query = str_replace(array('?'), array('\'%s\''), $customers->toSql());
            // $query = vsprintf($query, $customers->getBindings());
            // echo $query; 
           $results = $customers->get();
           $resultsSelected = [];
        }
        else{
            //get Assigned Customers of all location

            $customersSelected = DB::table('customer_creation_profiles as a')
            ->where('b.bdm_id', $userID)
            ->where('b.assign_status', '1')
            ->select(
                'a.id',
                'a.customer_name',
                'b.id as rowid',
                'b.customer_id',
                'b.bdm_id',
                'a.country',
                'c.country_name',
                'a.state',
                's.state_name',
                'd.district_name',
                'a.district',
                'a.city',
                'ct.city_name',
                'a.mobile_no',
                'b.assign_status'
            )
            ->join('bdm_has_customers as b', 'a.id', 'b.customer_id')
           
            ->join('country_masters as c','a.country','c.id')
            ->join('state_masters as s','a.state','s.id')
            ->join('district_masters as d','a.district','d.id')
            ->join('city_masters as ct','a.city','ct.id');

           $resultsSelected = $customersSelected->get();



            $customers = DB::table('customer_creation_profiles as a')
            ->select(
                'a.id',
                'a.customer_name',
                'b.id as rowid',
                'b.customer_id',
                'b.bdm_id',
                'a.country',
                'c.country_name',
                'a.state',
                's.state_name',
                'd.district_name',
                'a.district',
                'a.city',
                'ct.city_name',
                'a.mobile_no',
                'b.assign_status'
            )
            ->leftjoin('bdm_has_customers as b', 'a.id', 'b.customer_id')

            ->whereNotIn('a.id', function ($query) use ($userID) {
                $query->select('customer_id')
                    ->from('bdm_has_customers')->where('assign_status', '1');
            })
            
            
            ->join('country_masters as c','a.country','c.id')
            ->join('state_masters as s','a.state','s.id')
            ->join('district_masters as d','a.district','d.id')
            ->join('city_masters as ct','a.city','ct.id');


            if(!empty($request->country))
            {
            $customers->where('a.country',$request->country);
            }
            if(!empty($request->state))
            {
                $customers->where('a.state',$request->state);
            }
            if(!empty($request->district))
            {
                $customers->where('a.district', $request->district);
            }

            
            // $query = str_replace(array('?'), array('\'%s\''), $customers->toSql());
            // $query = vsprintf($query, $customers->getBindings());
            // echo $query; 
           $results = $customers->get();

        }

  
    if(count($resultsSelected)>0){
      $list =  array_merge(json_decode($resultsSelected),json_decode($results));
    }
    else{
        $list =$results;
    }

    $count = count($results);

    if($results){
        return  response()->json([
            'title' => 'CallAssign',
            'header' => $header,
            'accessor' => $accessor,
            'customerList' =>   $list,
            'data' =>  $results,
            'selectedList' => $resultsSelected,
            'count' =>$count
        ], 200);
    } else {
        return  response()->json([
            'error' =>  "You are not authorized User",
        ], 400);
    }
}
}


public function CustomerCreationMaster(Request $request)
{

    $user = Token::where('tokenid', $request->tokenid)->first();   
    $userid = $user['userid'];
    if($userid){
        $tableName = 'customer_creation_profiles';
        $header = ['Customer Name','State Name','City Name','Customer Group'];
        $specificColumns = ['customer_name','state','city','smart_city'];
        $columnNames = DB::select("SHOW COLUMNS FROM $tableName");
        $filteredColumns = array_intersect($specificColumns, array_column($columnNames, 'Field'));
    $customercreationList = DB::table('customer_creation_profiles')
    ->join('country_masters','country_masters.id','customer_creation_profiles.country')
    ->join('state_masters','state_masters.id','customer_creation_profiles.state')
    ->join('district_masters','district_masters.id','customer_creation_profiles.district')
    ->join('city_masters','city_masters.id','customer_creation_profiles.city')
    ->where([
        'customer_creation_profiles.delete_status'=>0,
    ])
    ->select(
        'customer_creation_profiles.id',
        'customer_creation_profiles.customer_name',
        'country_masters.country_name',
        'state_masters.state_name',
        'city_masters.city_name',
        'customer_creation_profiles.smart_city'
    )
    ->get();

    $modifiedAccessor = array_map(function ($value) {
        if ($value === "state") {
            return "state_name";
        } elseif ($value === "city") {
            return "city_name";
        }
        return $value;
    }, $filteredColumns);
   
        return  response()->json([
           
            'data' => $customercreationList,
            'header'=>$header,
            'title'=>'Customer Creation',
            'accessor'=> $modifiedAccessor,
        ]);
    }
}

}
