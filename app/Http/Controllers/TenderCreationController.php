<?php

namespace App\Http\Controllers;

use App\Models\TenderCreation;
use App\Models\CustomerCreationProfile;
use App\Models\TenderTypeMaster;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
use App\Models\BidCreation_Creation;

class TenderCreationController extends Controller
{

    public function index()
    {
        $tendercreation = TenderCreation::orderBy('created_at', 'desc')->get();
        //->paginate(4)->appends(['sort'=>'ulb_name']);

        if ($tendercreation)
            return response()->json([
                'status' => 200,
                'tendercreation' => $tendercreation
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

        try {
            $user = Token::where("tokenid", $request->tokenId)->first();
            $request->request->add(['cr_userid' => $user['userid']]);
            $request->request->remove('tokenId');

            if ($user['userid']) {
                $validator = Validator::make($request->all(), ['organisation' => 'required|string', 'customername' => 'required|integer',  'tendertype' => 'required|integer', 'nitdate' => 'required', 'cr_userid' => 'required']);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'errors' => $validator->messages(),
                    ]);
                }

                $tenderObj = TenderCreation::firstOrCreate($request->all());
                if ($tenderObj) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Tender Created Succssfully!'
                    ]);
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    public function show($id)

    {try{
        $tender = TenderCreation::find($id);

        if ($tender){

            // $customer_name = CustomerCreationProfile::find($tender['customername']);
            $customer_name = CustomerCreationProfile::where("id",$tender['customername'])
            ->select('customer_name','state')->get();

            
    
            if ($customer_name){
                $tender['nameOfCustomer'] = $customer_name[0]->customer_name; 

                $stateValue = null;
                if($customer_name[0]->state){
                    $state = StateMaster::find($customer_name[0]->state);
                    $stateValue = ["value" => $state['id'], "label" =>  $state['state_name']];
                }

                $tender['stateId'] = $stateValue;
            }

   // {
     //   try {
       //     $tender = TenderCreation::find($id);


         //   if ($tender) {

                // $customer_name = CustomerCreationProfile::find($tender['customername']);
           //     $customer_name = CustomerCreationProfile::where("id", $tender['customername'])
               //     ->select('customer_name', 'state')->get();

             //   if ($customer_name) {
              //      $tender['nameOfCustomer'] = $customer_name[0]->customer_name;
              //      $tender['stateId'] = $customer_name[0]->state;
              // }

                return response()->json([
                    'status' => 200,
                    'tender' => $tender
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect!'
                ]);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    public function edit(TenderCreation $tenderCreation)
    {
        //
    }

    public function gettendertrack()
    {
        $today = date('Y-m-d'); 

        $tendertracker = BidCreation_Creation::join('state_masters', 'bid_creation__creations.state', '=', 'state_masters.id')
            ->whereNotIn('bid_creation__creations.id', function ($query) {
                $query->select('bidid')->from('bid_management_tender_or_bid_stauses')
                ->where('status','Cancel');
            })
            ->where('bid_creation__creations.submissiondate','>=',$today)
            ->select('bid_creation__creations.*', 'state_masters.state_code')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($tendertracker)
         {   return response()->json([
                'status' => 200,
                'tendertracker' => $tendertracker
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function gettrackList(Request $request)
    {
return 'gettrackList';
        $qty_type = $request->quality;
        $state = $request->state;

        $tendertracker = BidCreation_Creation::join('state_masters', 'bid_creation__creations.state', '=', 'state_masters.id')

            ->select('bid_creation__creations.*', 'state_masters.state_code')
            ->where(function ($query) use ($qty_type, $request) {
                if ($qty_type == 'morethan50') {
                    return $query->where('quality', '>', 50000);
                } else if ($qty_type == 'between') {
                    return $query->whereBetween('quality', ['50000', '100000']);
                } else if ($qty_type == 'lessthan1lk') {
                    return $query->where('quality', '<', 100000);
                }
            })
            ->where(function ($query1) use ($state, $request) {
                if ($state != '' || $state != null) {
                    return $query1->where('state', $state);
                }
            })

            ->orderBy('created_at', 'desc')
            ->get();
        if ($tendertracker)
            return response()->json([
                'status' => 200,
                'tendertracker' => $tendertracker
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $tenderCreationResult2 = TenderCreation::where('tendercreation', '=', $request->tendercreation)
                ->where('id', '!=', $id)
                ->where('customer_id', '=', $request->customer_id)
                ->exists();
            if ($tenderCreationResult2) {
                return response()->json([
                    'status' => 400,
                    'errors' => 'Tender Type Already Exists'
                ]);
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'customername' => 'required|string',
                    'customer_id' => 'required',
                    'oraganization' => 'required',
                    'nitdate' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            }



            $tender = TenderCreation::findOrFail($id)->update($request->all());
            if ($tender)
                return response()->json([
                    'status' => 200,
                    'message' => "Updated Successfully!"
                ]);
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderCreation  $tenderCreation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tender = TenderCreation::destroy($id);
            if ($tender) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!",
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect!?',
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


    public function getTenderList($customerId)
    {

        $tenders = TenderCreation::where("customerId", $customerId)->get();
        //dd($states);
        $tenderList = array();
        foreach ($tenders as $tender) {
            $tenderList[] = ["value" => $tender['id'], "label" =>  $tender['tendertype']];
        }
        return  response()->json([
            'tenderList' =>  $tenderList
        ]);
    }





    public function getTender($savedstate)
    {

        $tender = TenderTypeMaster::where("tendertype", "!=", "")->first();

        $tenderid = $tender['id'];

        $tenders = TenderCreation::where("tenderid", $tenderid)
            ->where("tendertype", "!=", "")
            ->orWhere("id", $savedstate)
            ->orderBy('tendertype', 'ASC')
            ->get();
        //dd($states);
        $tenderList = array();
        foreach ($tenders as $tender) {
            $tenderList[] = ["value" => $tender['id'], "label" =>  $tender['tendertype']];
        }
        return  response()->json([
            'tenderList' =>  $tenderList
        ]);
    }

    public function getTenderListOptions($customerId, $savedstate)
    {
        // if($category === "state"){$cat = "State";}
        // if($category === "unionterritory"){$cat = "Union Territory";}

        DB::enableQueryLog();
        $tenders = TenderCreation::where("customerId", $customerId)
            // ->where("category",$cat)
            ->whereIn("customerId", function ($query) {
                $query->select('id')
                    ->from('tender_type_masters')
                    ->where("tendertype", "!=", "");
            })
            // ->where("state_status", "=", "Active")
            ->orWhere("id", function ($query) use ($customerId, $savedstate) {
                $query->select('id')
                    ->from('tender_creations')
                    ->where('id', $savedstate)
                    ->where('customerId', $customerId);
            })
            ->get();
        //dd($states);

        $sqlquery = DB::getQueryLog();

        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);

        $tenderList = array();
        foreach ($tenders as $tender) {
            $tenderList[] = ["value" => $tender['id'], "label" =>  $tender['tendertype'], 'oragnization' => 
            $tender['oragnization']];
            // $oragnization['oragnization']];
        }

        return  response()->json([
            'tenderList' =>  $tenderList,
            'sqlquery' => $query,
        ]);
    }

    public function getList()
    {

        $tendertypes = TenderTypeMaster::where("tendertype", "!=", "")->get();

        $tendertypeList = [];
        foreach ($tendertypes as $tendertype) {
            $tendertypeList[] = ["value" => $tendertype['id'], "label" =>  $tendertype['tendertype']];
        }
        return  response()->json([
            'tendertypeList' =>  $tendertypeList,

        ]);
    }

    public function TenderTrackerTable(Request $request)
    {
        
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
            $header = ['Location Name','State','Quantity','Unit','Price per unit','Estimate Project value(INR)','Tender / Processing Fee(INR)','EMD(INR)','EMD Mode','Per Bid Date','Submission Date'];
            $accessor = ['location','state_code','quality','unit','priceperunit','estprojectvalue','tenderfeevalue','emdamt','emdmode','prebiddate','submissiondate'];

            $qty_type = $request->quality;
            $state = $request->state;
    
            $tendertracker = BidCreation_Creation::join('state_masters', 'bid_creation__creations.state', '=', 'state_masters.id')
    
                ->select('bid_creation__creations.*', 'state_masters.state_code')
                ->where(function ($query) use ($qty_type, $request) {
                    if ($qty_type == 'morethan50') {
                        return $query->where('quality', '>', 50000);
                    } else if ($qty_type == 'between') {
                        return $query->whereBetween('quality', ['50000', '100000']);
                    } else if ($qty_type == 'lessthan1lk') {
                        return $query->where('quality', '<', 100000);
                    }
                })
                ->where(function ($query1) use ($state, $request) {
                    if ($state != '' || $state != null) {
                        return $query1->where('state', $state);
                    }
                })
    
                ->orderBy('created_at', 'desc')
                ->get();

                $count = count($tendertracker);

            if ($tendertracker)
                return response()->json([
                    'status' => 200,
                    'title' => 'TenderTracker',
                    'header' => $header,
                    'accessor' => $accessor,
                    'data' => $tendertracker,
                    'count' => $count,
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
