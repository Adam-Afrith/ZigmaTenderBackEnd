 // callcount for dashboard and bdm users 
 public function getCallCountAnalysis(Request $request)
    {
        // try {
        $today = Carbon::now()->toDateString();

            //$user = Token::where('tokenid', $request->tokenid)->firstOrFail();
           // $userid = $user->userid;

           $user = Token::where('tokenid', $request->tokenid)->first();
           $userid = $user['userid'];
           if($userid){
        $currentDate = date('Y-m-d'); // Get the current date
      //  $date = '2023-03-29';
//$userId = 1;

$todayCallsCount = DB::table('calltobdms AS a')
        ->leftJoin('calltobdm_has_customers AS b', 'b.calltobdm_id', '=', 'a.id')
        ->where('a.created_at', 'LIKE', "%$today%")
        ->where(function ($query) use ($userid) {
            $query->where(function ($query) use ($userid) {
                $query->where('a.created_userid', '<>', $userid)
                      ->where('a.user_id', '=', $userid);
            })
            ->orWhere(function ($query) use ($userid) {
                $query->where('a.user_id', '<>', $userid)
                      ->where('a.created_userid', '=', $userid);
            });
        })
        ->count();



        $openingCallsCount =  DB::table('calltobdms as a')
        ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
        ->leftJoin('call_log_creations as c', function($join) {
            $join->on('c.customer_id', '=', 'b.customer_id')
                ->where('c.customer_id', '!=', '');
        })
        ->where(function ($query) use ($userid) {
            $query->where('a.created_userid', '!=', $userid)
                  ->where('a.user_id', '=', $userid);
            })
        ->orWhere(function ($query) use ($userid) {
            $query->where('a.user_id', '!=', $userid)
                  ->where('a.created_userid', '=', $userid);
        })
        ->where('c.call_date', 'NOT LIKE', '%2023-04-04 %')
        ->where('c.action', '!=', 'close')
        ->count('c.id');


        $attendedCallsCount= DB::table('calltobdms as a')
        ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
        ->leftJoin('call_log_creations as c', function($join) {
            $join->on('c.customer_id', '=', 'b.customer_id')
                ->where('c.customer_id', '!=', '');
        })
        ->where(function ($query) use ($userid) {
            $query->where('a.created_userid', '!=', $userid)
                  ->where('a.user_id', '=', $userid);
            })
        ->orWhere(function ($query) use ($userid) {
            $query->where('a.user_id', '!=', $userid)
                  ->where('a.created_userid', '=', $userid);
        })
        ->where('c.call_date', 'LIKE', '%2023-04-04 %')
        ->count('c.id');

        $ClosedCallsCount = DB::table('calltobdms as a')
        ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
        ->leftJoin('call_log_creations as c', function($join) {
            $join->on('c.customer_id', '=', 'b.customer_id')
                ->where('c.customer_id', '!=', '');
        })
        ->select('c.customer_id', 'b.customer_id', 'a.id', 'b.calltobdm_id', 'a.created_userid', 'a.user_id')
        ->where(function ($query) use ($userid) {
            $query->where('a.created_userid', '!=', $userid)
                  ->where('a.user_id', '=', $userid);
            })
        ->orWhere(function ($query) use ($userid) {
            $query->where('a.user_id', '!=', $userid)
                  ->where('a.created_userid', '=', $userid);
        })
        ->where('c.action', '=', 'close')
        ->where('c.customer_id', '!=', '')
        ->count('c.id');

        $overduecallcount = DB::table('calltobdms as a')
        ->leftJoin('calltobdm_has_customers as b', 'b.calltobdm_id', '=', 'a.id')
        ->leftJoin('call_log_creations as c', function($join) {
            $join->on('c.customer_id', '=', 'b.customer_id')
                ->where('c.customer_id', '!=', '');
        })
        ->select('c.customer_id', 'b.customer_id', 'a.id', 'b.calltobdm_id', 'a.created_userid', 'a.user_id')
        ->where(function ($query) use ($userid) {
            $query->where('a.created_userid', '!=', $userid)
                  ->where('a.user_id', '=', $userid); 
            })
        ->orWhere(function ($query) use ($userid) {
            $query->where('a.user_id', '!=', $userid)
                  ->where('a.created_userid', '=', $userid);
        })
        ->where('c.action', '=', 'next_followup')
        ->where('c.customer_id', '!=', '')
        ->count('c.id');


            return response()->json([
                'status' => 200,
                'userid'=>$userid,
                'todaycallCount' => $todayCallsCount, //how many calls assigned bdm to calltobdm-has_customers
                'openingCallCount' => $openingCallsCount, //not closed calls except today
                'completedCallCount' => $ClosedCallsCount,  //completed calls 
                'attendedCallsCount' => $attendedCallsCount,//how many calls received as per today only
                'overduecallcount' => $overduecallcount,//next foollow up calls 
            ]);
        }
        // } catch (\Exception $ex) {

        //     return response()->json([
        //         'status' => 204,
        //         'message' => "Somthing Wrong",
        //         'error' => $ex
        //     ]);
        // }
    }