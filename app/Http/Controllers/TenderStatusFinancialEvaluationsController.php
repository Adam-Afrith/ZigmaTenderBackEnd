<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusFinancialEvaluations;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class TenderStatusFinancialEvaluationsController extends Controller
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

    public function getStoredFinEvalData($id){
        $storedFinEvalList = DB::table('tender_status_financial_evaluations')
        ->where('bidid',$id)
        ->get();

        if($storedFinEvalList){
            return response()->json([
                'storedFinEvalList' => $storedFinEvalList
            ]);
        }else{
            return response()->json([
                'storedFinEvalList' => []
            ]);
        }
    }

    public function store(Request $request)
    {
        //
          //get the user id 
          $user = Token::where('tokenid', $request->tokenid)->first();   
          $userid = $user['userid'];
          $financialEvaluation = null;
          $updatearray = [];
          if($userid){
              if($request->bidid){

                $bidid = $request->bidid;
                $getTechEvauationMainId = DB::table('tender_status_tech_evaluations')
                ->where('bidid', $bidid)
                ->get();

                if(sizeof($getTechEvauationMainId)>0){
                    $qualifiedList = DB::table('tender_status_tech_evaluations_subs')
                    ->join('competitor_profile_creations','tender_status_tech_evaluations_subs.competitorId','competitor_profile_creations.id')
                    ->where('tender_status_tech_evaluations_subs.qualifiedStatus', 'qualified')
                    ->where('tender_status_tech_evaluations_subs.techMainId', $getTechEvauationMainId[0]->id)
                    ->select('tender_status_tech_evaluations_subs.id','tender_status_tech_evaluations_subs.techMainId','tender_status_tech_evaluations_subs.competitorId', 'tender_status_tech_evaluations_subs.qualifiedStatus', 'tender_status_tech_evaluations_subs.reason', 'competitor_profile_creations.compName') 
                    ->orderBy('tender_status_tech_evaluations_subs.id', 'asc')       
                    ->get();
    
                    foreach ($qualifiedList as $qualifiedCompetitor){
                        
                        $isStoredFinEval = TenderStatusFinancialEvaluations::where([
                            ['bidid', $request->bidid],
                            ['techsubId', $qualifiedCompetitor->id],
                            ['competitorId', $qualifiedCompetitor -> competitorId],
                            ])->first();
                        
                        if($isStoredFinEval){
                            $isStoredFinEval -> update([
                                'amt' =>  $request->finEvaluation[$qualifiedCompetitor->id]['amt'] ?? null,
                                'unit' =>  $request->finEvaluation[$qualifiedCompetitor->id]['unit'] ?? null,
                                'least' => $request->finEvaluation[$qualifiedCompetitor->id]['least'] ?? '',
                                'edited_by' => $userid,
                            ]);

                            $updatearray[] = $isStoredFinEval;
                        }else{
                            $financialEvaluation = new TenderStatusFinancialEvaluations;
                            $financialEvaluation -> techsubId   = $qualifiedCompetitor->id;
                            $financialEvaluation -> bidid   = $bidid ;
                            $financialEvaluation -> competitorId = $qualifiedCompetitor -> competitorId;
                            $financialEvaluation -> amt =  $request->finEvaluation[$qualifiedCompetitor->id]['amt'] ?? null;
                            $financialEvaluation -> unit =  $request->finEvaluation[$qualifiedCompetitor->id]['unit'] ?? null;
                            $financialEvaluation -> least =  $request->finEvaluation[$qualifiedCompetitor->id]['least'] ?? '';
                            $financialEvaluation ->created_by =  $userid;
                            $financialEvaluation -> save();
                        }

                    }
                }
            }
          }
  
          if($financialEvaluation){
              return response()->json([
                  'status' => 'success',
                  'msg' => 'Submitted successfully',
              ]);
          }else if(sizeof($updatearray)>0){
            return response()->json([
                'status' => 'success',
                'msg' => 'updated successfully',
            ]);
          }else{
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to save!',
            ]);
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderStatusFinancialEvaluations  $tenderStatusFinancialEvaluations
     * @return \Illuminate\Http\Response
     */
    public function show(TenderStatusFinancialEvaluations $tenderStatusFinancialEvaluations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderStatusFinancialEvaluations  $tenderStatusFinancialEvaluations
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderStatusFinancialEvaluations $tenderStatusFinancialEvaluations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderStatusFinancialEvaluations  $tenderStatusFinancialEvaluations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TenderStatusFinancialEvaluations $tenderStatusFinancialEvaluations)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            if($request->bidid){
                $bidid = $request->bidid;
                $getTechEvauationMainId = DB::table('tender_status_tech_evaluations')
                ->where('bidid', $bidid)
                ->get();

                
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderStatusFinancialEvaluations  $tenderStatusFinancialEvaluations
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderStatusFinancialEvaluations $tenderStatusFinancialEvaluations)
    {
        //
    }


    public function getleastbidder($id)
    {
        $bidders = TenderStatusFinancialEvaluations::join('competitor_profile_creations', 'tender_status_financial_evaluations.competitorId', 'competitor_profile_creations.id')
            ->select('tender_status_financial_evaluations.*', 'competitor_profile_creations.compName')
            ->where('tender_status_financial_evaluations.bidid', $id)
        ->get();
        // $query = str_replace(array('?'), array('\'%s\''), $bidders->toSql());
        // $query = vsprintf($query, $bidders->getBindings());
        // echo $query;
        return response()->json([
            'status' => 200,
            'bidders' => $bidders,
        ]);
    }
}
