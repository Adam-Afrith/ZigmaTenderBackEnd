<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Mail;
use App\Mail\WeeklyMail;
use App\Models\BidCreation_Creation;
use Illuminate\Support\Carbon;
use App\Models\BidCreationTenderParticipation;
use App\Models\BidCreationBidSubmittedStatus;
use App\Models\StateMaster;
use App\Models\CustomerCreationProfile;
use Illuminate\Http\Request;
use App\Models\CountryMaster;
use App\Models\DistrictMaster;
use App\Models\CityMaster;

class AutoWeeklyMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-weekly-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
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
    ->wherehas('tenderParticipations',function($query)
    {
        $query->where('tenderparticipation', 'participating');
     })
    ->wherehas('bidSubmittedStatuses',function($query)
    {
            $query->where('bidSubmittedStatus','<>','Yes');
    }) ->whereBetween('submissiondate', [$currentDateAsString, $oneWeekAheadAsString])
    ->select('id','id as bidid','state','ulb','bidno','submissiondate','NITdate')
    ->selectRaw("DATEDIFF(submissiondate, CURDATE()) as daysDifference")
    ->get();
    // $bidCreations = BidCreation_Creation::join('bid_creation_tender_participations', 'bid_creation_tender_participations.bidCreationMainId', 'bid_creation__creations.id')
    // ->join('bid_creation_bid_submitted_statuses', 'bid_creation_bid_submitted_statuses.bidCreationMainId', 'bid_creation__creations.id')
    // ->where('bid_creation_tender_participations.tenderparticipation','participating')->where('bid_creation_bid_submitted_statuses.bidSubmittedStatus','<>','Yes')
    // ->whereBetween('submissiondate', [$currentDateAsString, $oneWeekAheadAsString])
    // ->select('bid_creation__creations.id as bidid', 'bid_creation_tender_participations.tenderparticipation as tenderparticipation', 'bid_creation_bid_submitted_statuses.bidSubmittedStatus as submitstatus','bid_creation__creations.state as state','bid_creation__creations.ulb as ulb','bid_creation__creations.bidno as bidno','bid_creation__creations.submissiondate as submissiondate','bid_creation__creations.NITdate as nitdate')
    // ->selectRaw("DATEDIFF(submissiondate, CURDATE()) as daysDifference")
    // ->get();
    
        if( $bidCreations)
        {
            $list=[];
            foreach($bidCreations as $item)
            {
                
                $customer_name = $item->customer->customer_name;
                $country = $item->customer->countrys->country_name;
                $state = $item->states->state_name;
                $district_name = $item->customer->districts->district_name;
                $city_name = $item->customer->citys->city_name; 

                $list[]=['customername'=>$customer_name,'BidNo'=>$item->bidno,'country'=>$country,'state'=>$state,'district'=>$district_name,'city'=>$city_name,'nitdate'=>$item->NITdate,'submissiondate'=>$item->submissiondate,'Remainingdays'=>$item->daysDifference];
            }
            
        }
        if(empty($list))
        {
           return false;
        }
        else
        {

            Mail::to('vigneshwaran@santhila.co')->send(new WeeklyMail($details,$list));
            return  'Email Sent Successfully...!';
        }
       
    }
}
