<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenderStatusBidders;
use App\Models\TenderStatusTechEvaluation;
use App\Models\TenderStatusTechEvaluationSub;
use App\Models\TenderStatusFinancialEvaluations;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class TenderStatusBiddersController extends Controller
{
    public function store(Request $request)
    {
        // try {
        $user = Token::where("tokenid", $request->tokenId)->first();

        //  $validator = Validator::make($request->all(), ['bidid' => 'required|integer','bidders' => 'required|integer','created_userid'=>'required|integer']);
        //  if ($validator->fails()) {
        //      return response()->json([
        //          'status' => 404,
        //          // 'message' =>"Not able to Add Strength/Weakness details now..!",
        //          'message' => $validator->messages(),
        //      ]);
        //  }
        if ($user['userid']) {

            foreach ($request->input as $key => $value) {
                if( $value['compId'] !== null){
                    $bidders = new TenderStatusBidders;
                    $bidders->bidid = $request->bidid;
                    $bidders->created_userid = $user['userid'];
                    foreach ($request->input[$key] as $key1 => $value1) {
                        if ($key1 == "compId") {
                            if (!empty($value1['value'])) {
                                $bidders->competitorId = $value1['value'];
                            } else {
                                continue;
                            }
                        } else if ($key1 == "status") {
                            $bidders->acceptedStatus = $value1;
                        } else if ($key1 == "reason") {
                            $bidders->reason = $value1;
                        }
                    }
                    $bidders->save();
                }
            }




            //  $bidders =new TenderStatusBidders;
            //  $bidders->bidid=$request->bidid;
            // //  $bidders->no_of_bidders=$request->bidders;
            // $bidders->input = 
            //  $bidders->created_userid=$request->created_userid;
            //  $bidders->save();
            //  //$bidders = TenderStatusBidders::firstOrCreate($request->all());
            if ($bidders) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Added Succssfully!',
                ]);
            }
        }
        // } catch (\Exception $e) {
        //     $error = $e->getMessage();
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'The provided credentials are incorrect!',
        //         'error' => $error
        //     ]);
        // }
    }

    //Get and Retunr All the Bidders
    public function show($id)
    {
        $bidders = TenderStatusBidders::where("bidid", $id)
            ->select('id', 'bidid', 'competitorId', 'acceptedStatus', 'reason')
            ->get();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function edit($id)
    {
        echo "Edit Function";
    }


    public function update(Request $request, $id)
    {
        try {
            $user = Token::where("tokenid", $request->tokenId)->first();

            // $validator = Validator::make($request->all(), ['bidid' => 'required|integer','no_of_bidders' => 'required|integer','edited_userid'=>'required|integer']);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => 404,
            //         'errors' => $validator->messages(),
            //     ]);
            // }
            if ($user['userid']) {
                foreach ($request->input as $key => $value) {
                    if( $value['compId'] !== null){
                        $compId = "";
                        foreach ($request->input[$key] as $key1 => $value1) {
                            if ($key1 == "compId")
                                foreach ($value1 as $key3 => $objValue) {
                                    if ($key3 == "value") {
                                        $compId = $objValue;
                                    }
                                }
                            else if ($key1 == "status") {
                                $status = $value1;
                                if ($value1 == 'rejected') {
                                    $res = $this->removeRejectedEntry($compId, $request->bidid, $user['userid']);
                                }
                            } else if ($key1 == "reason") {
                                $reason = $value1;
                            }
                        }
                        $bidders = TenderStatusBidders::where("id", $key)
                            ->where("bidid", $request->bidid)->get();
                        if ($bidders) {
                            $update = TenderStatusBidders::where("id", $key)
                                ->where("bidid", $request->bidid)->update(array("acceptedStatus" => $status, 'reason' => $reason, 'competitorId' => $compId, 'updated_userid' => $user['userid']));
                            // echo "save res".$update;
                        }
                    }
                }
                // $bidders = TenderStatusBidders::where("bidid",$id)->update($request->all());
                if ($update)
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


    public function getBidders($id)
    {
        $bidders = TenderStatusBidders::where("bidid", $id)
            ->select("*")
            ->get()->first();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');

        if ($user['userid']) {
            $bidders = TenderStatusBidders::where("bidid", $id)->update($request->all());
            if ($bidders)
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
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function getAcceptedBidders($id)
    {

        $bidders = DB::table('tender_status_bidders')
            ->where("bidid", $id)
            ->select("tender_status_bidders.id", "tender_status_bidders.bidid", "tender_status_bidders.competitorId", "competitor_profile_creations.compName")
            ->join("competitor_profile_creations", "tender_status_bidders.competitorId", "competitor_profile_creations.id")
            ->where("acceptedStatus", "approved")
            ->get();
        if ($bidders) {
            return response()->json([
                'status' => 200,
                'bidders' => $bidders
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function BiddersTenderStatus(Request $request, $id)
    {
        echo $request->competitorId;
        // $bidders =new TenderStatusBidders;
        // $bidders->bidid=$id;
        // $bidders->competitorId='9';
        // $bidders->acceptedStatus='approved';
        // $bidders->created_userid=$id;
        // $bidders->save();
        // if ($bidders) {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Added Succssfully!',
        //     ]);
        // }
    }

    //To remove Technical Evaluation sub Table Entry when Bidders status has updated as rejected if Exists
    public function removeRejectedEntry($compid, $bidid, $userId)
    {

        try {
            if (!empty($compid) && !empty($bidid) && !empty($userId)) {
                $techsubid = TenderStatusTechEvaluation::join('tender_status_tech_evaluations_subs as sub', 'tender_status_tech_evaluations.id', 'sub.techMainId')
                    ->where('sub.competitorId', $compid)
                    ->where('tender_status_tech_evaluations.bidid', $bidid)
                    ->select('sub.id')
                    ->first();

                if ($techsubid) {

                    $techDestroyResult = TenderStatusTechEvaluationSub::destroy($techsubid['id']);

                    $techDestroyResult = TenderStatusFinancialEvaluations::where('techsubId', $techsubid['id'])->delete();

                    // //update least record order in tender_status_financial_evaluations, when deleting particular record
                }
                $finValueBidId = TenderStatusFinancialEvaluations::where('bidid', $bidid)
                    ->where('least', '!=', '')
                    ->where('least', '!=', null)
                    ->update(['least' => null, 'edited_by' => $userId]);



                // if ($finValueBidId)
                //     foreach ($finValueBidId as $key => $value) {
                //         echo " --- Loop Key : $key";
                //         $update = TenderStatusFinancialEvaluations::where("id", $key)
                //             ->where("bidid", $bidid)->get();
                //         $update->least = null;
                //         $update->edited_userid = $userId;
                //         $update->save();
                //         echo "  ---- Update least in Fin res :".$update;
                //         return;
                // }
                // }
            }
        } catch (\Exception $ex) {
            echo "Exception : $ex";
            return;
        }
    }
}
