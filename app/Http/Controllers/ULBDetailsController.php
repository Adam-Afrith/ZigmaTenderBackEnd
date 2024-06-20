<?php

namespace App\Http\Controllers;

use App\Models\ULBDetails;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\StateMaster;
use Illuminate\Support\Facades\DB;


class ULBDetailsController extends Controller
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



        if ($userid) {
            $CustomerCreation = new ULBDetails;
            $CustomerCreation->area = $request->ulbdetails['area'];
            $CustomerCreation->population2011 = $request->ulbdetails['population2011'];
            // $CustomerCreation ->presentpopulation = $request->ulbdetails['presentpopulation'];
            $CustomerCreation->wards = $request->ulbdetails['wards'];
            $CustomerCreation->households = $request->ulbdetails['households'];
            $CustomerCreation->commercial = $request->ulbdetails['commercial'];
            $CustomerCreation->ABbusstand = $request->ulbdetails['ABbusstand'];
            $CustomerCreation->CDbusstand = $request->ulbdetails['CDbusstand'];
            $CustomerCreation->market_morethan_oneacre = $request->ulbdetails['market_morethan_oneacre'];
            $CustomerCreation->market_lessthan_oneacre = $request->ulbdetails['market_lessthan_oneacre'];
            $CustomerCreation->lengthofroad = $request->ulbdetails['lengthofroad'];
            $CustomerCreation->lengthofrouteroad = $request->ulbdetails['lengthofrouteroad'];
            $CustomerCreation->lengthofotherroad = $request->ulbdetails['lengthofotherroad'];
            $CustomerCreation->lengthoflanes = $request->ulbdetails['lengthoflanes'];
            $CustomerCreation->lengthofpucca = $request->ulbdetails['lengthofpucca'];
            $CustomerCreation->lengthofcutcha = $request->ulbdetails['lengthofcutcha'];
            $CustomerCreation->parks = $request->ulbdetails['parks'];
            $CustomerCreation->parksforpublicuse = $request->ulbdetails['parksforpublicuse'];
            $CustomerCreation->tricycle = $request->ulbdetails['tricycle'];
            $CustomerCreation->bov = $request->ulbdetails['bov'];
            $CustomerCreation->bovrepair = $request->ulbdetails['bovrepair'];
            $CustomerCreation->lcv = $request->ulbdetails['lcv'];
            $CustomerCreation->lcvrepair = $request->ulbdetails['lcvrepair'];
            $CustomerCreation->compactor = $request->ulbdetails['compactor'];
            $CustomerCreation->hookloaderwithcapacity = $request->ulbdetails['hookloaderwithcapacity'];
            $CustomerCreation->compactorbin = $request->ulbdetails['compactorbin'];
            $CustomerCreation->hookloader = $request->ulbdetails['hookloader'];
            $CustomerCreation->tractortipper = $request->ulbdetails['tractortipper'];
            $CustomerCreation->lorries = $request->ulbdetails['lorries'];
            $CustomerCreation->jcb = $request->ulbdetails['jcb'];
            $CustomerCreation->bobcat = $request->ulbdetails['bobcat'];
            $CustomerCreation->sanitaryworkers_sanctioned = $request->ulbdetails['sanitaryworkers_sanctioned'];
            $CustomerCreation->sanitaryworkers_inservice = $request->ulbdetails['sanitaryworkers_inservice'];
            $CustomerCreation->sanitarysupervisor_sanctioned = $request->ulbdetails['sanitarysupervisor_sanctioned'];
            $CustomerCreation->sanitarysupervisor_inservice = $request->ulbdetails['sanitarysupervisor_inservice'];
            $CustomerCreation->permanentdrivers = $request->ulbdetails['permanentdrivers'];
            $CustomerCreation->regulardrivers = $request->ulbdetails['regulardrivers'];
            $CustomerCreation->publicgathering = $request->ulbdetails['publicgathering'];
            $CustomerCreation->secondarystorage = $request->ulbdetails['secondarystorage'];
            $CustomerCreation->transferstation = $request->ulbdetails['transferstation'];
            $CustomerCreation->households_animatorsurvey = $request->ulbdetails['households_animatorsurvey'];
            $CustomerCreation->assessments_residential = $request->ulbdetails['assessments_residential'];
            $CustomerCreation->assessments_commercial = $request->ulbdetails['assessments_commercial'];
            $CustomerCreation->cust_creation_mainid = $request->ulbdetails['cust_creation_mainid'];
            $CustomerCreation->createdby_userid = $userid;
            $CustomerCreation->updatedby_userid = 0;
            $CustomerCreation->save();
        }

        if ($CustomerCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'ULB Details Saved!',
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
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $UlbDetails = ULBDetails::where('cust_creation_mainid', $id)->get()->first();
        if ($UlbDetails) {
            return response()->json([
                'status' => 200,
                'ulbdetails' => $UlbDetails,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No data'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(ULBDetails $uLBDetails)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if (!$userid) {
            return response()->json([
                'status' => 400,
                'message' => "Unable to update!"
            ]);
        }

        $ULBDetails = ULBDetails::findOrFail($id)->update([
            'area' => $request->ulbdetails['area'],
            'population2011' => $request->ulbdetails['population2011'],
            // 'presentpopulation' => $request->ulbdetails['presentpopulation'],
            'wards' => $request->ulbdetails['wards'],
            'households' => $request->ulbdetails['households'],
            'commercial' => $request->ulbdetails['commercial'],
            'ABbusstand' => $request->ulbdetails['ABbusstand'],
            'CDbusstand' => $request->ulbdetails['CDbusstand'],
            'market_morethan_oneacre' => $request->ulbdetails['market_morethan_oneacre'],
            'market_lessthan_oneacre' => $request->ulbdetails['market_lessthan_oneacre'],
            'lengthofroad' => $request->ulbdetails['lengthofroad'],
            'lengthofrouteroad' => $request->ulbdetails['lengthofrouteroad'],
            'lengthofotherroad' => $request->ulbdetails['lengthofotherroad'],
            'lengthoflanes' => $request->ulbdetails['lengthoflanes'],
            'lengthofpucca' => $request->ulbdetails['lengthofpucca'],
            'lengthofcutcha' => $request->ulbdetails['lengthofcutcha'],
            'parks' => $request->ulbdetails['parks'],
            'parksforpublicuse' => $request->ulbdetails['parksforpublicuse'],
            'tricycle' => $request->ulbdetails['tricycle'],
            'bov' => $request->ulbdetails['bov'],
            'bovrepair' => $request->ulbdetails['bovrepair'],
            'lcv' => $request->ulbdetails['lcv'],
            'lcvrepair' => $request->ulbdetails['lcvrepair'],
            'compactor' => $request->ulbdetails['compactor'],
            'hookloaderwithcapacity' => $request->ulbdetails['hookloaderwithcapacity'],
            'compactorbin' => $request->ulbdetails['compactorbin'],
            'hookloader' => $request->ulbdetails['hookloader'],
            'tractortipper' => $request->ulbdetails['tractortipper'],
            'lorries' => $request->ulbdetails['lorries'],
            'jcb' => $request->ulbdetails['jcb'],
            'bobcat' => $request->ulbdetails['bobcat'],
            'sanitaryworkers_sanctioned' => $request->ulbdetails['sanitaryworkers_sanctioned'],
            'sanitaryworkers_inservice' => $request->ulbdetails['sanitaryworkers_inservice'],
            'sanitarysupervisor_sanctioned' => $request->ulbdetails['sanitarysupervisor_sanctioned'],
            'sanitarysupervisor_inservice' => $request->ulbdetails['sanitarysupervisor_inservice'],
            'permanentdrivers' => $request->ulbdetails['permanentdrivers'],
            'regulardrivers' => $request->ulbdetails['regulardrivers'],
            'publicgathering' => $request->ulbdetails['publicgathering'],
            'secondarystorage' => $request->ulbdetails['secondarystorage'],
            'transferstation' => $request->ulbdetails['transferstation'],
            'households_animatorsurvey' => $request->ulbdetails['households_animatorsurvey'],
            'assessments_residential' => $request->ulbdetails['assessments_residential'],
            'assessments_commercial' => $request->ulbdetails['assessments_commercial'],
            'cust_creation_mainid' => $request->ulbdetails['cust_creation_mainid'],
            'updatedby_userid' => $userid,
        ]);

        if ($ULBDetails)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ULBDetails  $uLBDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(ULBDetails $uLBDetails)
    {
        //
    }




    //Dashborad contents based on ulbdetails
    public function getulbyearlydetails()
    {
        //Return no of customers as in {id: stateid, year : tender_awarded_year, count : 'no_of_cusotmer's_contract_awarded', state_name: 'state Name'}

        // Customer Means, Contract Awarded to zigma() and not been completed (in the form Bids Managemnet->Work Order - > Project Details ->Target Date For Completion field)

        $UlbDetails = DB::table('customer_creation_profiles as c')
            ->join("state_masters as s", "s.id", "c.state")
            ->where("s.state_status", "Active")
            ->select(
                's.id',
                's.state_name',
                DB::raw('COUNT(c.id) as count'),
                DB::raw('YEAR(c.created_at) year'),
            )
            ->groupBy('year', 'c.state', 's.id', 's.state_name')
            ->orderBy("s.id", "asc")
            ->get();

        if ($UlbDetails)
            return response()->json([
                'status' => 200,
                'list' => $UlbDetails, //statewise response ulb count, population count
            ]);
        else {
            return response()->json([
                'list' => "No content"
            ], 204);
        }
    }


    //Dashborad contents based on ulbdetails
    public function getbidanalysis()
    {
        try {

            $retender = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->join('bid_management_tender_or_bid_stauses as a', 'a.bidid', 'b.id')
                ->where('a.status', 'Retender')
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $cancelled = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->join('bid_management_tender_or_bid_stauses as a', 'a.bidid', 'b.id')
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->where('a.status', 'Cancel')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $awarded = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->join('tender_status_contract_awarded as a', 'a.bidid', 'b.id')
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $in_fin_eval = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->whereIn('b.id', function ($query) {
                    $query->select('bidid')->from('tender_status_tech_evaluations')->groupBy('bidid');
                })
                ->whereNotIn('b.id', function ($query) {
                    $query->select('bidid')->from('tender_status_financial_evaluations')->groupBy('bidid');
                })
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $in_tech_eval = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->whereIn('b.id', function ($query) {
                    $query->select('bidid')->from('tender_status_bidders')->groupBy('bidid');
                })
                ->whereNotIn('b.id', function ($query) {
                    $query->select('bidid')->from('tender_status_tech_evaluations')->groupBy('bidid');
                })
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();


            $to_be_opened = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->join('bid_creation_bid_submitted_statuses as s', 's.bidCreationMainId', 'b.id')
                ->whereNotIn('b.id', function ($query) {
                    $query->select('bidid')->from('tender_status_bidders')->groupBy('bidid');
                })
                ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $bid_submitted = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->join('bid_creation_bid_submitted_statuses as s', 's.bidCreationMainId', 'b.id')
                ->select(DB::raw('count(c.id) as `count`'), DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state')
                ->get();

            $bid_details = DB::table('customer_creation_profiles as c')
                ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
                ->select(DB::raw('count(c.id) as count'), DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
                ->groupby('year', 'month', 'c.state', 'b.bidno')
                ->get();


            return response()->json([
                'status' => 200,
                'awarded' => $awarded, //year,month, state wise awarded tenders details
                'bid_submitted' => $bid_submitted, //year,month, state wise bid_submitted tenders details
                'to_be_opened' => $to_be_opened,  //year,month, state wise bid_submitted tenders details
                'bid_details' => $bid_details, //Totla bid count year,month, state wise bid_submitted tenders details
                'in_tech_eval' => $in_tech_eval, // Technical Evaluation process is in pending
                'in_fin_eval' => $in_fin_eval,  // Financial Evaluation process is in pending
                'cancelled' => $cancelled, // Cancelled Tenders
                'retender' => $retender, // Retender Tenders 

                // 'awarded' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), //year,month, state wise awarded tenders details
                // 'bid_submitted' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), //year,month, state wise bid_submitted tenders details
                // 'to_be_opened' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ),  //year,month, state wise bid_submitted tenders details
                // 'bid_details' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), //Totla bid count year,month, state wise bid_submitted tenders details
                // 'in_tech_eval' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), // Technical Evaluation process is in pending
                // 'in_fin_eval' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ),  // Financial Evaluation process is in pending
                // 'cancelled' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), // Cancelled Tenders
                // 'retender' => array(
                //     array('count' => 2, 'year' => 2023, 'month' => 1, 'state' => 31),
                //     array('count' => 1, 'year' => 2023, 'month' => 2, 'state' => 2)
                // ), // Retender Tenders 
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 204,
                'message' => "Somthing Wrong",
                'error' => $ex
            ]);
        }
    }

    public function tenderanalysis()
    {
        // $tender = DB::table('tender_creations as t')
        //     ->select(
        //         't.organisation',
        //         't.customername',
        //         'c.customer_no',
        //         'c.customer_category',
        //         'c.customer_name',
        //         'c.smart_city',
        //         'c.customer_sub_category',
        //         DB::raw('COUNT(bs.bidCreationMainId) as bid_submited_count FROM bid_creation_bid_submitted_statuses bs WHERE bs.bidSubmittedStatus = "Yes" AND bs.bidCreationMainId = b.id'),
        //         'c.state'
        //     )
        //     ->join('customer_creation_profiles as c', 'c.id', 't.customername')
        //     ->join('bid_creation__creations as b', 'b.tendercreation', 't.id')
        //     ->groupBy('c.state', 't.organisation', 't.tendertype', 'c.customer_sub_category')
        //     ->get();


        $tender = DB::table('tender_creations as t')
            ->select(
                't.organisation',
                't.customername',
                'c.customer_no',
                'c.customer_category',
                'c.customer_name',
                'c.smart_city',
                'c.customer_sub_category',
                DB::raw('(SELECT COUNT(bs.bidCreationMainId) FROM bid_creation_bid_submitted_statuses bs WHERE bs.bidSubmittedStatus = "Yes" AND bs.bidCreationMainId = b.id) AS bid_submited_count'),
                'c.state'
            )
            ->join('customer_creation_profiles as c', 'c.id', '=', 't.customername')
            ->join('bid_creation_creations as b', 'b.tendercreation', '=', 't.id')
            ->groupBy('c.state', 't.organisation', 't.customername', 't.tendertype', 'c.customer_sub_category');
        // ->get();




        // $tend = ;
        $query = str_replace(array('?'), array('\'%s\''), $tender->toSql());
        $query = vsprintf($query, $tender->getBindings());

        echo $query;
    }

    public function getulbpopulationdetails()
    {
        $UlbDetails = DB::table('customer_creation_profiles as c')
            ->join("state_masters as s", "s.id", "c.state")
            ->join("u_l_b_details as u", "c.id", "u.cust_creation_mainid")
            ->join("country_masters as co", "s.country_id", "co.id")
            ->join("customer_sub_categories as csub", "c.customer_sub_category", "csub.id")
            ->where("s.state_status", "Active")
            // ->groupBy('s.id','s.country_id','csub.id', 's.state_name','s.category','s.state_code')
            ->orderBy("s.id", "asc")
            ->select(
                's.id',
                's.state_name',
                's.category',
                's.state_code',
                's.country_id',
                'co.country_name',
                'c.customer_name',
                'c.smart_city',
                'csub.customersubcategory',
                // DB::raw('COUNT(c.id) as count'),
                'u.population2011'
            )
            ->get();

        //Query for customer count monthwise with year (excluted tender awarded status)
        // $monthwise = DB::table('customer_creation_profiles')
        // ->select(DB::raw('count(id) as `count`'),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
        // ->groupby('year', 'month')
        // ->get();

        if ($UlbDetails)
            return response()->json([
                'status' => 200,
                'ulbdetails' => $UlbDetails, //statewise response ulb count, population count
            ]);
        else {
            return response()->json([
                'list' => "No content"
            ], 204);
        }
    }

    // public function getulbdashboarddetails()
    public function getulbdashboarddetails()
    {
        //Return no of customers as in {id: stateid, year : tender_awarded_year, count : 'no_of_cusotmer's_contract_awarded', state_name: 'state Name'}

        // Customer Means, Contract Awarded to zigma() and not been completed (in the form Bids Managemnet->Work Order - > Project Details ->Target Date For Completion field)

        $UlbDetails = DB::table('customer_creation_profiles as c')
            ->join("state_masters as s", "s.id", "c.state")
            ->where("s.state_status", "Active")
            ->select(
                's.id',
                's.state_name',
                DB::raw('COUNT(c.id) as count'),
                DB::raw('YEAR(c.created_at) year'),
            )
            ->groupBy('year', 'c.state', 's.id', 's.state_name')
            ->orderBy("s.id", "asc")
            ->get();


        // $query = str_replace(array('?'), array('\'%s\''), $UlbDetails->toSql());
        // $query = vsprintf($query, $UlbDetails->getBindings());
        // // dump($query);

        // return $query ;

        //Query for customer count monthwise with year (excluted tender awarded status)
        // $monthwise = DB::table('customer_creation_profiles')
        // ->select(DB::raw('count(id) as `count`'),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
        // ->groupby('year', 'month')
        // ->get();

        // $awarded = DB::table('customer_creation_profiles as c')
        //     ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
        //     ->join('tender_status_contract_awarded as a', 'a.bidid', 'b.id')
        //     ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
        //     ->groupby('year', 'month', 'c.state')
        //     ->get();

        // $participated = DB::table('customer_creation_profiles as c')
        //     ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
        //     ->join('bid_creation_tender_participations as p', 'p.bidCreationMainId', 'b.id')
        //     ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
        //     ->groupby('year', 'month', 'c.state')
        //     ->where('p.tenderparticipation', 'participating')
        //     ->get();

        // // $bid_submitted = DB::table('customer_creation_profiles as c')
        // //     ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
        // //     ->join('bid_creation_bid_submitted_statuses as s', 's.bidCreationMainId', 'b.id')
        // //     ->select(DB::raw('count(c.id) as `count`'), DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
        // //     ->groupby('year', 'month', 'c.state')
        // //     ->get();

        // $retender = DB::table('customer_creation_profiles as c')
        //     ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
        //     ->join('bid_management_tender_or_bid_stauses as a', 'a.bidid', 'b.id')
        //     ->where('a.status', 'Retender')
        //     ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
        //     ->groupby('year', 'month', 'c.state')
        //     ->get();

        // $cancelled = DB::table('customer_creation_profiles as c')
        //     ->join('bid_creation__creations as b', 'b.ulb', 'c.id')
        //     ->join('bid_management_tender_or_bid_stauses as a', 'a.bidid', 'b.id')
        //     ->select(DB::raw('count(c.id) as `count`'),  DB::raw('YEAR(c.created_at) year, MONTH(c.created_at) month'), 'c.state')
        //     ->where('a.status', 'Cancel')
        //     ->groupby('year', 'month', 'c.state')
        //     ->get();

        if ($UlbDetails)
            return response()->json([
                'status' => 200,
                'list' => $UlbDetails, //statewise response ulb count, population count
                // 'awarded' => $awarded, //year,month, state wise awarded tenders details
                // 'bid_submitted' => $bid_submitted, //year,month, state wise bid_submitted tenders details
                // 'participating' => $participated,  //year,month, state wise Partisipating tenders details
                // 'cancelled' => $cancelled,
                // 'retender' => $retender

            ]);
        else {
            return response()->json([
                'list' => "No content"
            ], 204);
        }
    }
    public function getulbreport(Request $request)
    {
    
    
    /***
     *SELECT a.customersubcategory as customersubcategory ,count(b.id) as customers,

(SELECT count(*) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where c.population2011 >  2000000 and b.customer_sub_category=a.id) as more_20,

(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id) as btw_10_20 ,

(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id) as btw_5_10,

(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id) as btw_3_5
,
(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id) as btw_1_3 ,

(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where c.population2011 < 100000 and b.customer_sub_category=a.id) as bel_1

 , count(
    (SELECT count(*) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where c.population2011 >  2000000 and b.customer_sub_category=a.id)
     +
    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id)
     +
    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id)
     +
    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id)
     +
     (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id)
     +
    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where c.population2011 < 100000 and b.customer_sub_category=a.id)
 
 ) as total FROM customer_sub_categories as a join customer_creation_profiles as b on a.id=b.customer_sub_category where a.status!='InActive' group by b.customer_sub_category;
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    $getquery='';
$customer_category =$request->category;
$state =$request->State;
$group =$request->group;
if($customer_category!=''){$cat="b.customer_category='".$customer_category."'";}else{$cat='';}
if($state!=''){$state1="b.state=".$state;}else{$state1='';}
if($group!=''){$smart_city="b.smart_city='".$group."'";}else{$smart_city='';}
$val =$cat.'@@'.$state1.'@@'.$smart_city;

$explode =explode('@@',$val);

foreach($explode as $record ){
    if($record!=''){
        $getquery.=$record.' and ';
    }
   
    
}

     $UlbReport = DB::table('customer_sub_categories as a')
     ->join("customer_creation_profiles as b", "a.id", "b.customer_sub_category")
     ->where("a.status", "!=","InActive")
     ->when($customer_category, function ($query) use ($customer_category) {
                    
        return $query->where('b.customer_category', $customer_category);
              
})
->when($group, function ($query) use ($group) {
                    
    return $query->where('b.smart_city', $group);
          
})
->when($state, function ($query) use ($state) {
                    
    return $query->where('b.state', $state);
          
})
     
     ->select('a.customersubcategory',
     DB::raw('count(b.id) as customers'),
     DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011  > 2000000 and b.customer_sub_category=a.id) as more_20"),

         DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id) as btw_10_20"),

         DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery  `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id) as btw_5_10"),

         DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id) as btw_3_5"),

         DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id) as btw_1_3"),
         DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 < 100000 and b.customer_sub_category=a.id) as bel_1"),
         DB::raw("(
            (SELECT count(*) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 >  2000000 and b.customer_sub_category=a.id)
             +
            (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery  `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id)
             +
            (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id)
             +
            (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id)
             +
             (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id)
             +
            (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 < 100000 and b.customer_sub_category=a.id)
         
         ) as total")
     
     
     )
     ->groupBy("b.customer_sub_category")
     ->orderBy("a.id", "asc")
       ->get();

//   $query = str_replace(array('?'), array('\'%s\''), $UlbReport->toSql());
//      $query = vsprintf($query, $UlbReport->getBindings());

//      echo $query;
     
     if ($UlbReport)
     return response()->json([
         'status' => 200,
         'list' => $UlbReport, //statewise response ulb count, population count
         // 'awarded' => $awarded, //year,month, state wise awarded tenders details
         // 'bid_submitted' => $bid_submitted, //year,month, state wise bid_submitted tenders details
         // 'participating' => $participated,  //year,month, state wise Partisipating tenders details
         // 'cancelled' => $cancelled,
         // 'retender' => $retender

     ]);
 else {
     return response()->json([
         'list' => "No content"
     ], 204);
 }
    
    }

    
    public function setpopupUlb(Request $request)
    {
    
    /*******
     * 
     * 
     * SELECT a.state as state ,a.cutomer_name as cutomer_name ,b.district_name as district_name , c.city_name as city_name ,d.population2011 as population  
     * 
     * FROM customer_sub_categories as sub join sub.id=a.customer_sub_category
     * 
     *  join  customer_creation_profiles as a 
     * 
     * join district_masters as b on a.district=b.id 
     * 
     * join city_masters as c on a.city=c.id 
     * 
     * join u_l_b_details as d on a.id=d.cust_creation_mainid WHERE 1
     * 
     * 
     * 
     * 
     * 
     * 
     */

     $customer_category =$request->filter_cat;
$state =$request->filter_state;
$group =$request->filter_group;
$POPUP =$request->POPUP;

$ULBnames =$request->ULBnames;
    $UlbPopup = DB::table('customer_sub_categories as a')
     ->join("customer_creation_profiles as b", "a.id", "b.customer_sub_category")
     ->join("district_masters as c", "b.district", "c.id")
     ->join("city_masters as d", "b.city", "d.id")
     ->join("u_l_b_details as e", "b.id", "e.cust_creation_mainid")
     ->join("state_masters as f","f.id","b.state")
     ->when($customer_category, function ($query) use ($customer_category) {
                    
        return $query->where('b.customer_category', $customer_category);
              
})
->when($group, function ($query) use ($group) {
                    
    return $query->where('b.smart_city', $group);
          
})
->when($state, function ($query) use ($state) {
                    
    return $query->where('b.state', $state);
          
})

->where(function ($query) use ($POPUP, $request) {


    if ($POPUP == '> 20 Lakh') {
      return $query->where('e.population2011', '>' ,2000000);
    }
    else if($POPUP == '10 - 20 Lakh'){
        return $query->whereBetween('e.population2011', [1000000, 2000000]);

        }
        else if($POPUP == '5 - 10 Lakh'){
            return $query->whereBetween('e.population2011', [500000, 1000000]);

        }
        else if($POPUP == '3 - 5 Lakh'){
            return $query->whereBetween('e.population2011', [300000, 500000]);

        }
        else if($POPUP == '1 - 3 Lakh'){
            return $query->whereBetween('e.population2011', [100000, 300000]);
        }
        else if($POPUP == '< 1 Lakh'){
            
            return $query->where('e.population2011','<' , 100000);

        }else{
return '';
        }
  })
  ->where('a.customersubcategory','like',$ULBnames)
     ->select('b.id','b.customer_category','b.customer_name','c.district_name','d.city_name','e.population2011','f.state_name')

     ->orderBy("b.id", "asc")
     ->groupBy("b.id")
     ->get();

    //  $query = str_replace(array('?'), array('\'%s\''), $UlbPopup->toSql());
    //  $query = vsprintf($query, $UlbPopup->getBindings());

    //  echo $query;
     
  if ($UlbPopup)
    return response()->json([
        'status' => 200,
        'UlbPopup' => $UlbPopup, //statewise response ulb count, population count
        // 'awarded' => $awarded, //year,month, state wise awarded tenders details
        // 'bid_submitted' => $bid_submitted, //year,month, state wise bid_submitted tenders details
        // 'participating' => $participated,  //year,month, state wise Partisipating tenders details
        // 'cancelled' => $cancelled,
        // 'retender' => $retender

    ]);
else {
    return response()->json([
        'UlbPopup' => "No content"
    ], 204);
}
    }



  
    public function ULBDetailsMaster(Request $request){

        $accessor =[];
        $getquery='';
        $header = ['ULB List','Number of City','> 20 Lakh','10 - 20 Lakh','5 - 10 Lakh','3 - 5 Lakh','1 - 3 Lakh','< 1 Lakh'];
        $customer_category =$request->category;
        $state =$request->State;
        $group =$request->group;
        if($customer_category!=''){$cat="b.customer_category='".$customer_category."'";}else{$cat='';}
        if($state!=''){$state1="b.state=".$state;}else{$state1='';}
        if($group!=''){$smart_city="b.smart_city='".$group."'";}else{$smart_city='';}
        $val =$cat.'@@'.$state1.'@@'.$smart_city;
        
        $explode =explode('@@',$val);
        
        foreach($explode as $record ){
            if($record!=''){
                $getquery.=$record.' and ';
            }
           
            
        }
        
             $UlbReport = DB::table('customer_sub_categories as a')
             ->join("customer_creation_profiles as b", "a.id", "b.customer_sub_category")
             ->where("a.status", "!=","InActive")
             ->when($customer_category, function ($query) use ($customer_category) {
                            
                return $query->where('b.customer_category', $customer_category);
                      
        })
        ->when($group, function ($query) use ($group) {
                            
            return $query->where('b.smart_city', $group);
                  
        })
        ->when($state, function ($query) use ($state) {
                            
            return $query->where('b.state', $state);
                  
        })
             
             ->select('a.customersubcategory',
             DB::raw('count(b.id) as customers'),
             DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011  > 2000000 and b.customer_sub_category=a.id) as more_20"),
        
                 DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id) as btw_10_20"),
        
                 DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery  `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id) as btw_5_10"),
        
                 DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id) as btw_3_5"),
        
                 DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id) as btw_1_3"),
                 DB::raw("(SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 < 100000 and b.customer_sub_category=a.id) as bel_1"),
                 DB::raw("(
                    (SELECT count(*) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 >  2000000 and b.customer_sub_category=a.id)
                     +
                    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery  `population2011` BETWEEN 1000000 AND 2000000 and b.customer_sub_category=a.id)
                     +
                    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 500000 AND 1000000 and b.customer_sub_category=a.id)
                     +
                    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 300000 AND 500000 and b.customer_sub_category=a.id)
                     +
                     (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery `population2011` BETWEEN 100000 AND 300000 and b.customer_sub_category=a.id)
                     +
                    (SELECT count(b.id) FROM  customer_creation_profiles as b  join u_l_b_details as c on b.id=c.cust_creation_mainid where $getquery c.population2011 < 100000 and b.customer_sub_category=a.id)
                 
                 ) as total")
             
             
             )
             ->groupBy("b.customer_sub_category")
             ->orderBy("a.id", "asc")
               ->get();
        
        
            foreach($UlbReport[0] as $key => $value){
    
                $accessor[] = $key;
            }
           
             if ($UlbReport)
             
             return response()->json([
                 'status' => 200,
                 'data' => $UlbReport, 
                 'header'=> $header,
                'title'=>'ULB Report List',
                'accessor'=>  $accessor,
        
             ]);
         else {
             return response()->json([
                 'list' => "No content"
             ], 204);
         }
        
    }





}
