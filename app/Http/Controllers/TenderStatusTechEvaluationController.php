<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusTechEvaluation;
use Illuminate\Support\Facades\DB;
use App\Models\TenderStatusTechEvaluationSub;
use App\Models\TenderStatusFinancialEvaluations;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;


class TenderStatusTechEvaluationController extends Controller
{

    public function store(Request $request)
    {
        // try {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if ($user['userid']) {
            $isBididExist = TenderStatusTechEvaluation::where("bidid", $request->bid_creation_mainid)->first();
            if (!$isBididExist) {
                // $validator = Validator::make($request->all(), ['organisation' => 'required|string', 'customername' => 'required|integer',  'tendertype' => 'required|integer', 'nitdate'=>'required', 'cr_userid'=>'required']);
                // if ($validator->fails()) {
                //     return response()->json([
                //         'status' => 400,
                //         'errors' => $validator->messages(),
                //     ]);
                // }

                if ($request->hasFile('file') && !empty($request->file)) {
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                    $ext =  $file->getClientOriginalExtension();
                    $filenameSplited = explode(".", $fileName1);
                    if ($filenameSplited[1] != $ext) {
                        $fileName = $filenameSplited[0] . "." . $ext;
                    } else {
                        $fileName = $fileName1;
                    }
                    $file->storeAs('BidManagement/techevaluation', $fileName, 'public');
                }
                $techEval = new TenderStatusTechEvaluation;
                $techEval->bidid = $request->bid_creation_mainid;
                $techEval->evaluationDate = $request->date;
                $techEval->document = !empty($fileName) ? $fileName : "";
                $techEval->created_userid = $user['userid'];
                $techEval->save();

                if ($techEval->id) {
                    foreach ($request->input as $key => $value) {
                        $insertsub = new TenderStatusTechEvaluationSub;
                        $insertsub->techMainId = $techEval->id;
                        $insertsub->created_userid = $user['userid'];
                        $insertsub->competitorId = $key;
                        foreach ($request->input[$key] as $key1 => $value1) {
                            if ($key1 == "status") {
                                $insertsub->qualifiedStatus = $value1;
                            } else if ($key1 == "reason") {
                                $insertsub->reason = $value1;
                            }
                        }
                        $insertsub->save();
                    }
                }


                if ($techEval && $insertsub) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Technial Evaluation Status Added..!'
                    ]);
                } else if ($techEval && !$insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into sub table'
                    ]);
                } else if (!$techEval && $insertsub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into main table'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Technical Evalution Details Already Exist For this Tender ..!',
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function show(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mainId)
    {

        try {
            $user = Token::where("tokenid", $request->tokenid)->first();
            // Have to get Existing Data from table to update using update Method
            $getExistingData = TenderStatusTechEvaluation::where("id", $mainId)->get()->first();

            if ($user['userid']) {
                $fileName = "";
                if ($request->hasFile('file') && !empty($request->file)) {
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                    $ext =  $file->getClientOriginalExtension();
                    $filenameSplited = explode(".", $fileName1);
                    if ($filenameSplited[1] != $ext) {
                        $fileName = $filenameSplited[0] . "." . $ext;
                    } else {
                        $fileName = $fileName1;
                    }
                    if ($getExistingData['document']) {
                        //to delete existin image                    
                        $image_path = public_path('uploads/BidManagement/techevaluation') . '/' . $getExistingData['document'];
                        $path = str_replace("\\", "/", $image_path);
                        unlink($path);
                    }
                    $file->storeAs('BidManagement/techevaluation', $fileName, 'public');
                }


                $getExistingData->bidid = $request->bid_creation_mainid;
                $getExistingData->evaluationDate = $request->date;
                $getExistingData->document = !empty($fileName) ? $fileName : "";
                $getExistingData->edited_userid = $user['userid'];
                $getExistingData->save();

                if ($getExistingData->id) {
                    foreach ($request->input as $key => $value) {
                        $updatesub = TenderStatusTechEvaluationSub::where("competitorId", $key)->where("techMainId", $mainId)->first();
                        if ($updatesub) {
                            foreach ($request->input[$key] as $key1 => $value1) {
                                if ($key1 == "status") {
                                    $updatesub->qualifiedStatus = $value1;
                                    if ($value1 == 'not qualified') {
                                        $res = $this->removeRejectedEntry($key, $request->bid_creation_mainid, $updatesub->id, $user['userid']);
                                        // echo "Res $res";
                                    }
                                } else if ($key1 == "reason") {
                                    $updatesub->reason = $value1;
                                }
                            }
                            $updatesub->edited_userid = $user['userid'];
                            $updatesub->save();
                        } else {
                            $insertsub = new TenderStatusTechEvaluationSub;
                            $insertsub->techMainId = $getExistingData->id;
                            $insertsub->created_userid = $user['userid'];
                            $insertsub->competitorId = $key;
                            foreach ($request->input[$key] as $key1 => $value1) {
                                if ($key1 == "status") {
                                    $insertsub->qualifiedStatus = $value1;
                                } else if ($key1 == "reason") {
                                    $insertsub->reason = $value1;
                                }
                            }
                            $insertsub->save();
                        }
                    }
                }


                if ($getExistingData && $updatesub) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Technial Evaluation Status Updated..!'
                    ]);
                } else if ($getExistingData && !$updatesub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Update..!',
                        'err' => 'not able to insert into sub table'
                    ]);
                } else if (!$getExistingData && $updatesub) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Oops, Unable to Add..!',
                        'err' => 'not able to insert into main table'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderStatusTechEvaluation  $tenderStatusTechEvaluation
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderStatusTechEvaluation $tenderStatusTechEvaluation)
    {
        //
    }

    public function getTechEvaluationList($id)
    {
        try {
            $fetchsubtable = DB::table('tender_status_tech_evaluations as main')
                ->where('main.bidid', $id)
                ->get()->first();

            if ($fetchsubtable->id) {
                $fetchresult = DB::table('tender_status_tech_evaluations_subs as sub')
                    ->where('sub.techMainId',  $fetchsubtable->id)
                    ->select('sub.competitorId', 'sub.qualifiedStatus', 'sub.reason')
                    ->get();
                if ($fetchresult)
                    return response()->json([
                        'status' => 200,
                        'result' => $fetchresult,
                        'date' => $fetchsubtable->evaluationDate,
                        'mainId' => $fetchsubtable->id,
                        'filename' => $fetchsubtable->document,
                    ]);
                else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'The provided credentials are incorrect.'
                    ]);
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id)
    {
        $doc = TenderStatusTechEvaluation::where('bidid', $id)
            ->select("document")
            ->get();

        if (!empty($doc[0]['document'])) {
            $file = public_path() . "/uploads/BidManagement/techevaluation/" . $doc[0]['document'];
            return response()->download($file, $doc[0]['document']);
        } else {
            return response()->json([
                '$file' => 'File not found.'
            ], 204);
        }
    }


    public function getQualifiedList($id)
    {

        $bidid = $id;
        $qualifiedList = "";
        $getTechEvauationMainId = DB::table('tender_status_tech_evaluations')
            ->where('bidid', $bidid)
            ->get();

        if (sizeof($getTechEvauationMainId) > 0) {
            $qualifiedList = DB::table('tender_status_tech_evaluations_subs')
                ->join('competitor_profile_creations', 'tender_status_tech_evaluations_subs.competitorId', 'competitor_profile_creations.id')
                ->join('tender_status_tech_evaluations', 'tender_status_tech_evaluations_subs.techMainId', 'tender_status_tech_evaluations.id')
                ->join('tender_status_bidders', function ($join) {
                    $join->on('tender_status_tech_evaluations.bidid', 'tender_status_bidders.bidid')
                        ->on('tender_status_tech_evaluations_subs.competitorId', 'tender_status_bidders.competitorId');
                })
                ->where('tender_status_tech_evaluations_subs.qualifiedStatus', 'qualified')
                ->where('tender_status_bidders.acceptedStatus', 'approved')
                ->where('tender_status_tech_evaluations_subs.techMainId', $getTechEvauationMainId[0]->id)
                ->select('tender_status_tech_evaluations_subs.id', 'tender_status_tech_evaluations_subs.techMainId', 'tender_status_tech_evaluations_subs.competitorId', 'tender_status_tech_evaluations_subs.qualifiedStatus', 'tender_status_tech_evaluations_subs.reason', 'competitor_profile_creations.compName')
                ->orderBy('tender_status_tech_evaluations_subs.id', 'asc')
                ->get();

            // $qualifiedList1 = DB::table('tender_status_tech_evaluations_subs')
            // ->join('competitor_profile_creations','tender_status_tech_evaluations_subs.competitorId','competitor_profile_creations.id')
            // ->where('tender_status_tech_evaluations_subs.qualifiedStatus', 'qualified')
            // ->where('tender_status_tech_evaluations_subs.techMainId', $getTechEvauationMainId[0]->id)
            // ->select('tender_status_tech_evaluations_subs.id','tender_status_tech_evaluations_subs.techMainId','tender_status_tech_evaluations_subs.competitorId', 'tender_status_tech_evaluations_subs.qualifiedStatus', 'tender_status_tech_evaluations_subs.reason', 'competitor_profile_creations.compName') 
            // ->orderBy('tender_status_tech_evaluations_subs.id', 'asc');

            // $query = str_replace(array('?'), array('\'%s\''), $qualifiedList->toSql());
            // $query = vsprintf($query, $qualifiedList->getBindings());
            // echo $query;
        }

        if ($qualifiedList) {
            return response()->json([
                'qualifiedList' => $qualifiedList,
                'getTechEvauationMainId' => $getTechEvauationMainId
            ]);
        } else {
            return response()->json([
                'qualifiedList' => []
            ]);
        }
    }

    public function removeRejectedEntry($compid, $bidid, $techsubId, $userId)
    {

        try {
            if (!empty($compid) && !empty($bidid) && !empty($userId) && !empty($techsubId)) {
                $finsubid = TenderStatusFinancialEvaluations::where('competitorId', $compid)
                    ->where('bidid', $bidid)
                    ->where('techsubId', $techsubId)
                    ->select('id')
                    ->first();

                if ($finsubid) {
                    // $techDestroyResult = DB::table('tender_status_financial_evaluations')
                    // ->where('techsubId', $techsubid['id'])->delete();

                    $FinRowDestroyResult = TenderStatusFinancialEvaluations::destroy($finsubid['id']);
                }
                // //update least record order in tender_status_financial_evaluations, when deleting particular record
                $finValueBidId = TenderStatusFinancialEvaluations::where('bidid', $bidid)
                    ->where('least', '!=', '')
                    ->where('least', '!=', null)
                    ->update(['least' => null, 'edited_by' => $userId]);
            }
        } catch (\Exception $ex) {
            echo "Exception : $ex";
            return;
        }
    }
}
