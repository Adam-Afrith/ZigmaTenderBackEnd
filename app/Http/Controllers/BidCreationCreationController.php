<?php

namespace App\Http\Controllers;

use App\Models\BidCreation_Creation;
use App\Models\BidCreation_Creation_Docs;
use App\Models\TenderCreation;
use App\Models\BidmanagementPreBidQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\StateMaster;
use App\Models\CustomerCreationProfile;
use App\Models\TenderStatusContractAwarded;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use App\Mail\WeeklyMail;
use Illuminate\Support\Facades\Mail;


class BidCreationCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $bidCreation = DB::table('bid_creation__creations')->select('*')->orderBy('id', 'DESC')->get();

       
        $tenderCreation = DB::table('tender_creations')
        ->leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
        ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
        ->select('tender_creations.id AS tenderid', 'bid_creation__creations.id AS bidid', 'tender_creations.nitdate', 'tender_creations.customername', 'bid_creation__creations.quality', 'bid_creation__creations.unit', 'bid_creation__creations.submissiondate', 'customer_creation_profiles.customer_name')
        ->orderBy('tender_creations.nitdate', 'DESC')
        ->get();
          

        return response()->json([
            'tenderCreationList' =>   $tenderCreation,
            'bidcreationList' => []
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
       
         //get the user id 
         $user = Token::where('tokenid', $request->tokenid)->first();   
         $userid = $user['userid'];

         if($userid){
            $bidCreation = new BidCreation_Creation;
            $bidCreation -> bidno = $request->bidcreationData['bidno'];
            $bidCreation -> customername = $request->bidcreationData['customername'];
            $bidCreation -> bidcall = $request->bidcreationData['bidcall'];
            $bidCreation -> tenderid = $request->bidcreationData['tenderid'];
            $bidCreation -> tenderinvtauth = $request->bidcreationData['tenderinvtauth'];
            $bidCreation -> tenderref = $request->bidcreationData['tenderref'];
            $bidCreation -> state = $request->bidcreationData['state']['value'];
            $bidCreation -> ulb = $request->bidcreationData['ulb']['value'];
            $bidCreation -> TenderDescription = $request->bidcreationData['TenderDescription'];
            $bidCreation -> NITdate = $request->bidcreationData['NITdate'];
            $bidCreation -> submissiondate = $request->bidcreationData['submissiondate'];
            $bidCreation -> quality = $request->bidcreationData['quality'];
            $bidCreation -> unit = $request->bidcreationData['unit'];
            $bidCreation -> tenderevalutionsysytem = $request->bidcreationData['tenderevalutionsysytem'];
            $bidCreation -> projectperioddate1 = $request->bidcreationData['projectperioddate1'];
            $bidCreation -> projectperioddate2 = $request->bidcreationData['projectperioddate2'];
            $bidCreation -> estprojectvalue = $request->bidcreationData['estprojectvalue'];
            $bidCreation -> tenderfeevalue = $request->bidcreationData['tenderfeevalue'];
            $bidCreation -> priceperunit = $request->bidcreationData['priceperunit'];
            $bidCreation -> emdmode = $request->bidcreationData['emdmode'];
            $bidCreation -> emdamt = $request->bidcreationData['emdamt'];
            $bidCreation -> dumpsiter = $request->bidcreationData['dumpsiter'];
            $bidCreation -> prebiddate = $request->bidcreationData['prebiddate'];
            $bidCreation -> EMD = $request->bidcreationData['EMD']; 
            $bidCreation -> location = $request->bidcreationData['location'];
            $bidCreation -> tendercreation= $request->tenderid;
            $bidCreation -> createdby_userid = $userid ;
            $bidCreation -> updatedby_userid = 0 ;
            $bidCreation -> save();
        }

        if ($bidCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Bid Has created Succssfully!',
                'id' => $bidCreation['id'],
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
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $bidCreation_Creation = BidCreation_Creation::find($id);
        if ($bidCreation_Creation){

            $state = StateMaster::find($bidCreation_Creation['state']);
            $stateValue = ["value" => $state['id'], "label" =>  $state['state_name']];

            $ulb = CustomerCreationProfile::find($bidCreation_Creation['ulb']);
            $ulbValue = ["value" => $ulb['id'], "label" =>  $ulb['customer_name']];

            $bidCreation_Creation['state'] = $stateValue;
            $bidCreation_Creation['ulb'] = $ulbValue;

            return response()->json([
                'status' => 200,
                'bidcreationdata' => $bidCreation_Creation
            ]);
        }

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
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreation_Creation $bidCreation_Creation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $updatedata = $request->bidcreationData;
            $updatedata['updatedby_userid']= $userid;
            $updatedata['state']= $request->bidcreationData['state']['value'];
            $updatedata['ulb']= $request->bidcreationData['ulb']['value'];

            $bidcreationData = BidCreation_Creation::findOrFail($id)->update($updatedata);
        }

        if ($bidcreationData)
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
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function destroy($tender_creaton_id)
    {
        //
        try{

            // $bidcreation = BidCreation_Creation::where("tendercreation",$tender_creaton_id)->get();

            // if(count($bidcreation) > 0){

            //     $docs = BidCreation_Creation_Docs::where("bidCreationMainId",$bidcreation[0]['id'])->get();
                
            //     if($docs){
            //         foreach($docs as $doc){
            //             $document = BidCreation_Creation_Docs::find($doc['id']);
    
            //             $filename = $document['file_new_name'];
            //             $file_path = public_path()."/uploads/BidManagement/biddocs/".$filename;
            //             // $file_path =  storage_path('app/public/BidDocs/'.$filename);
            
            //             if(File::exists($file_path)) {
            //                 File::delete($file_path);
            //             }
            //         }
            //     }
    
            //     $prebiddocs = BidmanagementPreBidQueries::where("bidCreationMainId",$id)->get();
    
            //     if($prebiddocs){
            //         foreach($prebiddocs as $doc){
            //             $document = BidmanagementPreBidQueries::find($doc['id']);
    
            //             $filename = $document['file_new_name'];
            //             $file_path = public_path()."/uploads/BidManagement/prebidqueries/".$filename;
            //             // $file_path =  storage_path('app/public/BidDocs/'.$filename);
            
            //             if(File::exists($file_path)) {
            //                 File::delete($file_path);
            //             }
            //         }
            //     }
            // }




            $deleteBid = TenderCreation::destroy($tender_creaton_id);

            if($deleteBid)
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

    public function getBidList(Request $request){

           //
          $formdate =  $request->fromdate;
          $todate   =  $request->todate;
        

            // $tenderCreation = DB::table('tender_creations')
            // ->leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
            // ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')

            // ->when($formdate, function($query) use ($formdate) {
            //     return $query->where('tender_creations.nitdate','>=',$formdate);
            // })
            // ->when($todate, function($query) use ($todate) {
            //     return $query->where('tender_creations.nitdate','<=',$todate);
            // })
            // ->select('tender_creations.id AS tenderid', 'bid_creation__creations.id AS bidid', 'tender_creations.nitdate', 'tender_creations.customername', 'bid_creation__creations.quality', 'bid_creation__creations.unit', 'bid_creation__creations.submissiondate', 'customer_creation_profiles.customer_name')
            // ->orderBy('tender_creations.nitdate', 'DESC')
            // ->get();
          
            $queryBuilder = TenderCreation::leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
            ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
            ->join('state_masters as sm','sm.id','customer_creation_profiles.state')
            ->leftjoin('bid_management_tender_or_bid_stauses', 'bid_creation__creations.id', 'bid_management_tender_or_bid_stauses.bidid')
            ->select(
                'tender_creations.id AS tenderid', 
                'bid_creation__creations.id AS bidid', 
                'tender_creations.nitdate', 
                'tender_creations.customername',
                'bid_creation__creations.quality', 
                'bid_creation__creations.unit', 
                'bid_creation__creations.submissiondate', 
                'customer_creation_profiles.customer_name',
                'sm.state_name',
                )
            ->addselect(DB::raw("(CASE 
            WHEN bid_management_tender_or_bid_stauses.status = 'Cancel' THEN 'Tender Cancel'
            WHEN bid_management_tender_or_bid_stauses.status = 'Retender'  THEN 'Retender'
            WHEN (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE
                  bidid = bid_creation__creations.id) = 0 
                  THEN 'To Be Opened' 
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) = 0
                ) 
                  THEN 'Technical Evaluation in Progress'
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) = 0 
                ) 
                  THEN 'Financial Bid Opening in Progress'     
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) = 0 
                ) 
                  THEN 'LoA yet to be awarded' 
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) > 0 
                ) 
                  THEN 'Awarded'  
            ELSE ''
            END) as tenderStatus"));
          

            DB::enableQueryLog(); 
            $result = DB::table(DB::raw('(' . $queryBuilder->toSql() . ') as a'))
            ->mergeBindings($queryBuilder->getQuery())
            ->when($formdate, function($query) use ($formdate) {
                return $query->where('a.nitdate','>=',$formdate);
            })
            ->when($todate, function($query) use ($todate) {
                return $query->where('a.nitdate','<=',$todate);
            })
            ->orderBy('a.nitdate', 'DESC')
            ->get();
            $sqlquery = DB::getQueryLog();
        
            $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
            $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);
    

          return response()->json([
              'tenderCreationList'  => $result,
              'SqlQuery'            =>  $sqlquery,
          ]);

    }

    
public function getlegacylist(Request $request){

    $todaydate = date('Y-m-d'); 
   
  
    $tender_participation = $request->tenderparticipation;
    $formdate = $request->fromdate;
    $todate_req = $request->todate;
    $todate='';
    if($todate_req==''||$todate_req==null){
        $todate=date('Y-m-d');
    }else{
        $todate= $todate_req;
    }
  
    //$status	= $request->status;
    $state	=  $request->state;	
    
    $typeofproject =  $request->typeofproject;	
    $typeofcustomer =  $request->typeofcustomer;
    $tenderStatus =  $request->status;


    $queryBuilder = TenderCreation::leftjoin('bid_creation__creations', 'tender_creations.id','bid_creation__creations.tendercreation')
    ->leftjoin('bid_creation_tender_participations', 'bid_creation__creations.id', 'bid_creation_tender_participations.bidCreationMainId')
    ->leftjoin('bid_management_tender_or_bid_stauses', 'bid_creation__creations.id', 'bid_management_tender_or_bid_stauses.bidid')
    
    ->select('tender_creations.nitdate' ,
    'tender_creations.organisation',
    'bid_creation__creations.id as bidid',
    'bid_creation__creations.quality',
    'bid_creation__creations.location',
    'bid_creation__creations.submissiondate',
    'bid_creation__creations.unit',
    'bid_creation__creations.state',
    'bid_creation__creations.estprojectvalue',
    'tender_creations.customername',
    'bid_management_tender_or_bid_stauses.status'	)
    ->addselect(DB::raw("(CASE 
    WHEN bid_creation_tender_participations.tenderparticipation = 'participating' THEN 'participating'
    WHEN bid_creation_tender_participations.tenderparticipation = 'notparticipating'  THEN 'notparticipating'
    ELSE 'notparticipating'
    END) as tenderPar"))
    ->addselect(DB::raw("(CASE 
    WHEN bid_management_tender_or_bid_stauses.status = 'Cancel' THEN 'Tender Cancel'
    WHEN bid_management_tender_or_bid_stauses.status = 'Retender'  THEN 'Retender'
    WHEN (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE
          bidid = bid_creation__creations.id) = 0 
          THEN 'To Be Opened' 
    WHEN (
            (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
            (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) = 0
        ) 
          THEN 'Technical Evaluation in Progress'
    WHEN (
            (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
            (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
            (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) = 0 
        ) 
          THEN 'Financial Bid Opening in Progress'     
    WHEN (
            (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
            (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
            (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
            (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) = 0 
        ) 
          THEN 'LoA yet to be awarded' 
    WHEN (
            (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
            (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
            (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
            (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) > 0 
        ) 
          THEN 'Awarded'  
    ELSE ''
    END) as tenderStatus"));
    

    DB::enableQueryLog(); 
    $result = DB::table(DB::raw('(' . $queryBuilder->toSql() . ') as a'))
    ->mergeBindings($queryBuilder->getQuery())
   

    ->when($formdate && $todate, function($query) use ($formdate, $todate) {
      
        return $query->whereBetween('a.nitdate', [$formdate, $todate]);
    })
    ->when($state, function($query) use ($state) {
        return $query->where('a.state','=',$state);
    })
 
  
    ->when($typeofproject, function($query) use ($typeofproject) {
        return $query->leftjoin('customer_creation_s_w_m_project_statuses', 'a.customername', 'customer_creation_s_w_m_project_statuses.mainid' )
        ->where('customer_creation_s_w_m_project_statuses.projecttype', $typeofproject);    
    })
    ->when($typeofcustomer, function($query) use ($typeofcustomer){
        if($typeofcustomer === "Public"){
            return $query->where('a.organisation','Public Sector Unit');
        }
        if($typeofcustomer === "Private"){
            return $query->where('a.organisation','Private/Public Sector Company');
        }
    })
    ->when($tender_participation, function($query) use ($tender_participation){
        if($tender_participation === "Yes"){
            return $query->where('a.tenderPar','participating');
        }
        if($tender_participation === "No"){
            return $query->where('a.tenderPar','=','notparticipating');
        }
    })
    ->when($tenderStatus, function($query) use ($tenderStatus){
        if($tenderStatus === "To be Opened"){
            return $query->where('a.tenderStatus','To Be Opened');
        }
        if($tenderStatus === "Technical Evaluation in Progress"){
            return $query->where('a.tenderStatus','Technical Evaluation in Progress');
        }
        if($tenderStatus === "Financial Bid Opening in Progress"){
            return $query->where('a.tenderStatus','Financial Bid Opening in Progress');
        }
        if($tenderStatus === "LoA yet to be awarded"){
            return $query->where('a.tenderStatus','LoA yet to be awarded');
        }
        if($tenderStatus === "Awarded"){
            return $query->where('a.tenderStatus','Awarded');
        }
        if($tenderStatus === "Tender Cancelled"){
            return $query->where('a.tenderStatus','=','Tender Cancelled');
        }
        if($tenderStatus === "Retendered"){
            return $query->where('a.tenderStatus','Retender');
        }
        	
    })
    ->where('a.submissiondate', '<', $todaydate )
    ->orWhere('a.status', '=', 'Cancel' )
    ->orderBy('a.nitdate', 'ASC')
     ->get();


//         $sqlquery = $result;
    
//         $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
//         $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);
// echo $SQL;

// $query = str_replace(array('?'), array('\'%s\''), $result->toSql());
// $query = vsprintf($query, $result->getBindings());

//  echo $query;

      
    
        if ($result)
        return response()->json([
            'status' => 200,
            'legacylist' => $result,
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
        
}
    public function getlegacylist_backup(Request $request){

        $todaydate = date('Y-m-d'); 
      
        $tender_participation = $request->tenderparticipation;
        $formdate = $request->fromdate;
        $todate = $request->todate;
        $status	= $request->status;
		$state	=  $request->state;		
		$typeofproject =  $request->typeofproject;	
        $typeofcustomer =  $request->typeofcustomer;
     

        DB::enableQueryLog(); 
        $queryTenPar ='';
        $legacystatememnt = DB::table('tender_creations')
        ->leftjoin('bid_creation__creations', 'tender_creations.id','bid_creation__creations.tendercreation')
        ->leftjoin('bid_creation_tender_participations', 'bid_creation__creations.id', 'bid_creation_tender_participations.bidCreationMainId')
        
        ->where('bid_creation__creations.submissiondate' , '<', $todaydate)
        ->when($formdate, function($query) use ($formdate) {
            return $query->where('tender_creations.nitdate','>=',$formdate);
        })
        ->when($todate, function($query) use ($todate) {
            return $query->where('tender_creations.nitdate','<=',$todate);
        })
        ->when($state, function($query) use ($state) {
            return $query->where('bid_creation__creations.state','=',$state);
        })
        ->when($typeofproject, function($query) use ($typeofproject) {
            
            return $query->leftjoin('customer_creation_s_w_m_project_statuses', 'tender_creations.customername', 'customer_creation_s_w_m_project_statuses.mainid' )
            ->where('customer_creation_s_w_m_project_statuses.projecttype', $typeofproject);    
            // return $query->whereIn('customer_creation_profiles.id', function($query) use ($typeofproject){
            //     $query->select('id')->from('customer_creation_s_w_m_project_statuses')
            //     ->where('projecttype', $typeofproject);
            // });
        })
        ->when($typeofcustomer, function($query) use ($typeofcustomer){
            if($typeofcustomer === "Public"){
                return $query->where('tender_creations.organisation','Public Sector Unit');
            }
            if($typeofcustomer === "Private"){
                return $query->where('tender_creations.organisation','Private/Public Sector Company');
            }
        })

       
        // ->selectRaw('bid_creation__creations.NITdate' ,  'bid_creation__creations.quality', 'bid_creation__creations.submissiondate', 'bid_creation__creations.unit', 'bid_creation__creations.customername',
        // `(CASE 
        // WHEN bid_creation_tender_participations.tenderparticipation = 'participating' THEN "participating"
        // WHEN bid_creation_tender_participations.tenderparticipation = 'notparticipating' THEN "notparticipating"
        // ELSE "notparticipating"
        // END) as ternderParticipation)`)
        ->select('*', DB::raw("(CASE 
        WHEN bid_creation_tender_participations.tenderparticipation = 'participating' THEN 'participating'
        WHEN bid_creation_tender_participations.tenderparticipation = 'notparticipating'  THEN 'notparticipating'
        ELSE 'notparticipating'
        END) as tenderPar"))
        // ->selectRaw()
        ->when($tender_participation, function($query) use ($tender_participation){
            $queryTenPar = $query;
            if($tender_participation === "Yes"){
                return $query->where('bid_creation_tender_participations.tenderparticipation','participating');
            }
            if($tender_participation === "No"){
                return $query->where('bid_creation_tender_participations.tenderparticipation','=','notparticipating');
            }
        })
        ->orderBy('tender_creations.nitdate', 'DESC')
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);

        return response()->json([
            'legacylist' => $legacystatememnt,
            'sql'=>$SQL,
            'type_of_company' => $request->typeofcustomer,
            'tender_participation' => $request->tenderparticipation,
            'formdate' => $request->fromdate,
            'todate' => $request->todate,
            'queryTenPar' => $queryTenPar
        ]);
    }


     // Created by Brindha on 21.01.2023 for dashboard count
    public function live_tender()
    {
        $live_tender_count = BidCreation_Creation::whereDate('submissiondate', '>=', now())->count();
        // $live_tender = BidCreation_Creation::where('created_at', 'desc')->get();
     
        if ($live_tender_count)
            return response()->json([
                'status' => 200,
                'live_tender_count' => $live_tender_count
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
public function fresh_tender()
    {
        $fresh_tender_count = BidCreation_Creation::whereDate('created_at', '=', now())->count();
        // $live_tender = BidCreation_Creation::where('created_at', 'desc')->get();
      
        if ($fresh_tender_count)
            return response()->json([
                'status' => 200,
                'fresh_tender_count' => $fresh_tender_count
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function awarded_tenders()
    {
        $awarded_tender_count = TenderStatusContractAwarded::count();
        // $live_tender = BidCreation_Creation::where('created_at', 'desc')->get();
      
        if ($awarded_tender_count)
            return response()->json([
                'status' => 200,
                'awarded_tender_count' => $awarded_tender_count
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    
    public function getLastBidno($code){

        $lastbidno = BidCreation_Creation::select('bidno')
        ->where('bidno','Like',"%$code%") 
        ->get()
        ->last();
            
        if ($lastbidno)
            return response()->json([
                'status' => 200,
                'lastbidno' => $lastbidno
            ]);
        else {
            return response()->json([
                'status' => 404,
                'lastbidno' => $lastbidno,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

//retunrs running & Completed project counts
public function projectstatus()
{
    try {
        $run_count = DB::table('bid_management_work_order_project_details')
            ->where("commercialproduc", "!=", null)
            ->where('produccompletion', '=', null)
            ->count();

        $completed_count = DB::table('bid_management_work_order_project_details')
            // ->whereNotNull("commercialproduc")
            ->whereNotNull('produccompletion')
            ->count();

        $awarded_tender_count = TenderStatusContractAwarded::count();  
        $fresh_tender_count = BidCreation_Creation::whereDate('created_at', '=', now())->count();  
        $live_tender_count = BidCreation_Creation::whereDate('submissiondate', '>=', now())->count();

        return response()->json([
            'status' => 200,
            'running_tender_count' => $run_count,
            'completed_tender_count' => $completed_count,
            'awarded_tender_count' => $awarded_tender_count,
            'fresh_tender_count' => $fresh_tender_count,
            'live_tender_count' => $live_tender_count
        ]);
    } catch (\Exception $e) {
        $error = $e->getMessage();
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect',
            'error' => $error
        ]);
    }
}

public function BidManagementListTable(Request $request)
{
    $user = Token::where("tokenid", $request->tokenid)->first();
    if($user['userid'])
    {

        $header = ['NIT Date','Customer Name','State Name','Quantity of Legacy Waste','Unit','Submission Date','Status'];
        $accessor = ['nitdate','customername','state_name','quality','unit','submissiondate','tenderStatus'];

            $formdate =  $request->fromdate;
            $todate   =  $request->todate;
         
            $queryBuilder = TenderCreation::leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
            ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
            ->join('state_masters as sm','sm.id','customer_creation_profiles.state')
            ->leftjoin('bid_management_tender_or_bid_stauses', 'bid_creation__creations.id', 'bid_management_tender_or_bid_stauses.bidid')
            ->select(
                'tender_creations.id AS tenderid', 
                'bid_creation__creations.id AS bidid', 
                'tender_creations.nitdate', 
                'tender_creations.customername',
                'bid_creation__creations.quality', 
                'bid_creation__creations.unit', 
                'bid_creation__creations.submissiondate', 
                'customer_creation_profiles.customer_name',
                'sm.state_name',
                )
            ->addselect(DB::raw("(CASE 
            WHEN bid_management_tender_or_bid_stauses.status = 'Cancel' THEN 'Tender Cancel'
            WHEN bid_management_tender_or_bid_stauses.status = 'Retender'  THEN 'Retender'
            WHEN (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE
                  bidid = bid_creation__creations.id) = 0 
                  THEN 'To Be Opened' 
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) = 0
                ) 
                  THEN 'Technical Evaluation in Progress'
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) = 0 
                ) 
                  THEN 'Financial Bid Opening in Progress'     
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) = 0 
                ) 
                  THEN 'LoA yet to be awarded' 
            WHEN (
                    (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
                    (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
                    (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) > 0 
                ) 
                  THEN 'Awarded'  
            ELSE ''
            END) as tenderStatus"));
          

            DB::enableQueryLog(); 
            $result = DB::table(DB::raw('(' . $queryBuilder->toSql() . ') as a'))
            ->mergeBindings($queryBuilder->getQuery())
            ->when($formdate, function($query) use ($formdate) {
                return $query->where('a.nitdate','>=',$formdate);
            })
            ->when($todate, function($query) use ($todate) {
                return $query->where('a.nitdate','<=',$todate);
            })
            ->orderBy('a.nitdate', 'DESC')
            ->get();
            $sqlquery = DB::getQueryLog();
        
            $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
            $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);
    
            

          return response()->json([
                'title' => 'BidManagement',
                'header' => $header,
                'accessor' => $accessor,
                'data'  => $result,
               // 'SqlQuery'            =>  $sqlquery,
          ]);

    }

}

public function BidCreationMaster(Request $request)
{
    $header = ['NIT Date','Location','Quantity','Unit','Project Cost','Status'];
    $todaydate = date('Y-m-d');
    $accessor = [];
    $tender_participation = $request->tenderparticipation;
    $formdate = $request->fromdate;
    $todate_req = $request->todate;
    $todate = '';
    if ($todate_req == '' || $todate_req == null) {
        $todate = date('Y-m-d');
    } else {
        $todate = $todate_req;
    }

    //$status	= $request->status;
    $state = $request->state;

    $typeofproject = $request->typeofproject;
    $typeofcustomer = $request->typeofcustomer;
    $tenderStatus = $request->status;


    $queryBuilder = TenderCreation::leftjoin('bid_creation__creations', 'tender_creations.id', 'bid_creation__creations.tendercreation')
        ->leftjoin('bid_creation_tender_participations', 'bid_creation__creations.id', 'bid_creation_tender_participations.bidCreationMainId')
        ->leftjoin('bid_management_tender_or_bid_stauses', 'bid_creation__creations.id', 'bid_management_tender_or_bid_stauses.bidid')

        ->select(
            'tender_creations.nitdate',
            'tender_creations.organisation',
            'bid_creation__creations.id as bidid',
            'bid_creation__creations.quality',
            'bid_creation__creations.location',
            'bid_creation__creations.submissiondate',
            'bid_creation__creations.unit',
            'bid_creation__creations.state',
            'bid_creation__creations.estprojectvalue',
            'tender_creations.customername',
            'bid_management_tender_or_bid_stauses.status'
        )
        ->addselect(DB::raw("(CASE 
WHEN bid_creation_tender_participations.tenderparticipation = 'participating' THEN 'participating'
WHEN bid_creation_tender_participations.tenderparticipation = 'notparticipating'  THEN 'notparticipating'
ELSE 'notparticipating'
END) as tenderPar"))
        ->addselect(DB::raw("(CASE 
WHEN bid_management_tender_or_bid_stauses.status = 'Cancel' THEN 'Tender Cancel'
WHEN bid_management_tender_or_bid_stauses.status = 'Retender'  THEN 'Retender'
WHEN (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE
      bidid = bid_creation__creations.id) = 0 
      THEN 'To Be Opened' 
WHEN (
        (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
        (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) = 0
    ) 
      THEN 'Technical Evaluation in Progress'
WHEN (
        (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
        (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
        (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) = 0 
    ) 
      THEN 'Financial Bid Opening in Progress'     
WHEN (
        (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
        (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
        (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
        (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) = 0 
    ) 
      THEN 'LoA yet to be awarded' 
WHEN (
        (SELECT COUNT(*) AS tender_status_bidders FROM `tender_status_bidders` WHERE bidid = bid_creation__creations.id) > 0 AND 
        (SELECT COUNT(*) AS tech_evaluations FROM `tender_status_tech_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
        (SELECT COUNT(*) AS financial_evaluations FROM `tender_status_financial_evaluations` WHERE bidid = bid_creation__creations.id) > 0 AND
        (SELECT COUNT(*) AS contract_awarded FROM `tender_status_contract_awarded` WHERE bidid = bid_creation__creations.id) > 0 
    ) 
      THEN 'Awarded'  
ELSE ''
END) as tenderStatus"));


    DB::enableQueryLog();
    $result = DB::table(DB::raw('(' . $queryBuilder->toSql() . ') as a'))
        ->mergeBindings($queryBuilder->getQuery())


        ->when($formdate && $todate, function ($query) use ($formdate, $todate) {

            return $query->whereBetween('a.nitdate', [$formdate, $todate]);
        })
        ->when($state, function ($query) use ($state) {
            return $query->where('a.state', '=', $state);
        })


        ->when($typeofproject, function ($query) use ($typeofproject) {
            return $query->leftjoin('customer_creation_s_w_m_project_statuses', 'a.customername', 'customer_creation_s_w_m_project_statuses.mainid')
                ->where('customer_creation_s_w_m_project_statuses.projecttype', $typeofproject);
        })
        ->when($typeofcustomer, function ($query) use ($typeofcustomer) {
            if ($typeofcustomer === "Public") {
                return $query->where('a.organisation', 'Public Sector Unit');
            }
            if ($typeofcustomer === "Private") {
                return $query->where('a.organisation', 'Private/Public Sector Company');
            }
        })
        ->when($tender_participation, function ($query) use ($tender_participation) {
            if ($tender_participation === "Yes") {
                return $query->where('a.tenderPar', 'participating');
            }
            if ($tender_participation === "No") {
                return $query->where('a.tenderPar', '=', 'notparticipating');
            }
        })
        ->when($tenderStatus, function ($query) use ($tenderStatus) {
            if ($tenderStatus === "To be Opened") {
                return $query->where('a.tenderStatus', 'To Be Opened');
            }
            if ($tenderStatus === "Technical Evaluation in Progress") {
                return $query->where('a.tenderStatus', 'Technical Evaluation in Progress');
            }
            if ($tenderStatus === "Financial Bid Opening in Progress") {
                return $query->where('a.tenderStatus', 'Financial Bid Opening in Progress');
            }
            if ($tenderStatus === "LoA yet to be awarded") {
                return $query->where('a.tenderStatus', 'LoA yet to be awarded');
            }
            if ($tenderStatus === "Awarded") {
                return $query->where('a.tenderStatus', 'Awarded');
            }
            if ($tenderStatus === "Tender Cancelled") {
                return $query->where('a.tenderStatus', '=', 'Tender Cancelled');
            }
            if ($tenderStatus === "Retendered") {
                return $query->where('a.tenderStatus', 'Retender');
            }

        })
        ->where('a.submissiondate', '<', $todaydate)
        ->orWhere('a.status', '=', 'Cancel')
        ->orderBy('a.nitdate', 'ASC')
        ->get();
        //  return $result;
        foreach($result[0] as $key => $value){
            if($key === 'nitdate' || $key === 'location' || $key === 'quality' || $key === 'unit' || $key === 'unit' || $key === 'estprojectvalue' || $key === 'estprojectvalue' || $key === 'status' ){
                $accessor[] = $key;
            }
            
        }

      

        if ($result)
        return response()->json([
            'status' => 200,
            'data' => $result,
            'header'=> $header,
            'title'=>'Legacy Statement',
            'accessor'=>  $accessor,

        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }

}

public function mailstatus(Request $request)
{

   

    $details = [
        'subject' => 'Greetings',
        'sender' => 'vw341554@gmail.com',
        'recipient' => 'vigneshwaran@santhila.co',
        'body' => 'Hi GoodMorning...!'
    ];

    $currentDate = Carbon::now();
    $oneWeekAhead = $currentDate->copy()->addWeek();
    $currentDateAsString = $currentDate->toDateString();
    $oneWeekAheadAsString = $oneWeekAhead->toDateString();

    //    return 'currentDate = '.$currentDateAsString.'oneWeekAhead ='.$oneWeekAheadAsString;
    $bidCreations = BidCreation_Creation::with(['tenderParticipations:bidCreationMainId,tenderparticipation', 'bidSubmittedStatuses:bidCreationMainId,bidSubmittedStatus',])
        ->wherehas('tenderParticipations', function ($query) {
            $query->where('tenderparticipation', 'participating');
        })
        ->wherehas('bidSubmittedStatuses', function ($query) {
            $query->where('bidSubmittedStatus', '<>', 'Yes');
        })->whereBetween('submissiondate', [$currentDateAsString, $oneWeekAheadAsString])
        ->select('id', 'id as bidid', 'state', 'ulb', 'bidno', 'submissiondate', 'NITdate')
        ->selectRaw("DATEDIFF(submissiondate, CURDATE()) as daysDifference")
        ->get();
    if ($bidCreations) {
        $list = [];
        foreach ($bidCreations as $item)
         {

            $customer_name = $item->customer->customer_name;
            $country = $item->customer->countrys->country_name;
            $state = $item->states->state_name;
            $district_name = $item->customer->districts->district_name;
            $city_name = $item->customer->citys->city_name;

            //    $list[]=['customername'=>$customer_name,'BidNo'=>$item->bidno,'country'=>$country,'state'=>$state,'district'=>$district_name,'city'=>$city_name,'nitdate'=>$item->NITdate,'submission date'=>$item->submissiondate];
            $list[] = ['customername' => $customer_name, 'BidNo' => $item->bidno, 'country' => $country, 'state' => $state, 'district' => $district_name, 'city' => $city_name, 'nitdate' => $item->NITdate, 'submissiondate' => $item->submissiondate, 'Remainingdays' => $item->daysDifference];

        }

    }
    if (empty($list)) {
        return false;
    } else {

        Mail::to('vigneshwaran@santhila.co')->send(new WeeklyMail($details, $list));

        $emailContent = (new WeeklyMail($details, $list))->render();

        return view('test',['list'=>$list]);
    }


    // return response()->json([
    //     'status'=>200,
    //     'data'=> $list
    // ]);

    // $bidCreations = BidCreation_Creation::join('bid_creation_tender_participations', 'bid_creation_tender_participations.bidCreationMainId', 'bid_creation__creations.id')
    // ->join('bid_creation_bid_submitted_statuses', 'bid_creation_bid_submitted_statuses.bidCreationMainId', 'bid_creation__creations.id')
    // ->where('bid_creation_tender_participations.tenderparticipation','participating')->where('bid_creation_bid_submitted_statuses.bidSubmittedStatus','<>','Yes')
    // ->whereBetween('submissiondate', [$currentDateAsString, $oneWeekAheadAsString])
    // ->select('bid_creation__creations.id as bidid', 'bid_creation_tender_participations.tenderparticipation as tenderparticipation', 'bid_creation_bid_submitted_statuses.bidSubmittedStatus as submitstatus','bid_creation__creations.state as state','bid_creation__creations.ulb as ulb','bid_creation__creations.bidno as bidno','bid_creation__creations.submissiondate as submissiondate','bid_creation__creations.NITdate as nitdate')
    // ->selectRaw("DATEDIFF(submissiondate, CURDATE()) as daysDifference")
    // ->get();

    // if( $bidCreations)
    // {
    //     $list=[];
    //     foreach($bidCreations as $row)
    //     {

    //         // $stateValue = StateMaster::find($row['state']);

    //         // $state =  $stateValue->state_name;


    //         $ulb = CustomerCreationProfile::find($row['ulb']);
    //         $customer_name = $ulb->customer_name;
    //         // $co = CountryMaster::find($ulb->country);
    //         // $country = $co->country_name; 
    //         // $district = DistrictMaster::find($ulb->district);
    //         // $district_name = $district->district_name;
    //         // $city= CityMaster::find($ulb->city);
    //         // $city_name = $city->city_name;

    //         $country = $row->customer->countrys->country_name;
    //         $state = $row->customer->states->state_name;
    //         $district_name = $row->customer->districts->district_name;
    //         $city_name = $row->customer->citys->city_name;


    //         $list[]=['BidNo'=>$row->bidno,'country'=>$country,'state'=>$state,'district'=>$district_name,'city'=>$city_name,'customername'=>$customer_name,'submissiondate'=>$row->submissiondate,'NIT date'=>$row->nitdate,'Remainingdays'=>$row->daysDifference];
    //     }

    // }




    // return response()->json([
    //     'status'=>200,
    //     'data'=>$list
    // ]);




}

}
