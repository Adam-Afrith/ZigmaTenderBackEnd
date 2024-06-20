<?php

namespace App\Http\Controllers;

use App\Models\ExpensesApproval;
use App\Models\User;
use App\Models\OtherExpenses;
use App\Models\OtherExpenseSub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;


class ExpensesApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $exp_app = ExpensesApproval::get();

        if ($exp_app) {
            return response()->json([
                'status' => 200,
                'exp_app' => $exp_app,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No data'
            ]);
        }
    }
    public function get_staff_name()
    {

        $get_staff = User::get();

        if ($get_staff) {
            return response()->json([
                'status' => 200,
                'get_staff' => $get_staff,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No data'
            ]);
        }
    }

    public function showsub(Request $request)
    {

        $staff_id = $request->staff_id;

        /**************
         * 
         * 
         * 
         * SELECT a.id as id , a.entry_date as entry_date , b.userName as staff_name , (select sum(amount) from other_expense_subs where customer_id IS NOT NULL and call_no IS NOT NULL and mainid=a.id ) as call_amount, (select sum(amount) from other_expense_subs where customer_id IS NULL and call_no IS NULL and mainid=a.id ) as expense_amount FROM other_expenses AS a join users as b on b.id=a.executive_id where a.executive_id=1;
         * 
         * 
         * 
         * 
         * 
         * 
         */

        if ($staff_id != '' || $staff_id != null) {
            $get_sub = DB::table('other_expenses  as a')
                ->join('users as b', "a.executive_id", "b.id")
                ->select(
                    'a.id',
                    'a.entry_date',
                    'b.userName',
                    DB::raw("round((select sum(amount) from other_expense_subs where customer_id IS NOT NULL and call_no IS NOT NULL and mainid=a.id ),2) as call_amount"),
                    DB::raw("round((select sum(amount) from other_expense_subs where customer_id IS NULL and call_no IS NULL and mainid=a.id ),2) as expense_amount"),
                    'a.expense_no as expense_no'
                )
                ->where('executive_id', $staff_id)
                ->get();

            //  $query = str_replace(array('?'), array('\'%s\''), $get_sub->toSql());
            //      $query = vsprintf($query, $get_sub->getBindings());

            //      echo $query;



            // executive_id 

            if (count($get_sub) != 0) {
                return response()->json([
                    'status' => 200,
                    'list' => $get_sub
                ]);
            } else {

                return response()->json([
                    'status' => 400,
                    'message' => 'No data'
                ]);
            }
        } else {

            return response()->json([
                'status' => 400,
                'message' => 'No data'
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

        /****invoice Genaration  */
        $currentDate = date('mY');
        $lastRecord = ExpensesApproval::latest('ex_app_no')->first();

        if ($lastRecord) {
            $exp_rec = explode('-', $lastRecord->ex_app_no);
            $my = $exp_rec[1];

            if ($my == $currentDate) {
                $newInvoiceNumberPadded = str_pad($exp_rec[2] + 1, 4, '0', STR_PAD_LEFT);
                $invoiceNumber = 'EUA-' . $currentDate . '-' .  $newInvoiceNumberPadded;
            } else {

                $invoiceNumber = 'EUA-' . $currentDate . '-' . '0001';
            }
        } else {


            $invoiceNumber = 'EUA-' . $currentDate . '-' . '0001';
        }

        //  // Increment the last invoice number
        //  $newInvoiceNumber = $lastRecord + 1;

        //  // Pad the invoice number with leading zeros so it's always 4 digits
        //  $newInvoiceNumberPadded = str_pad($newInvoiceNumber, 4, '0', STR_PAD_LEFT);

        //  // Combine the invoice prefix with the current date and the padded invoice number
        //  $invoiceNumber = 'EUA-' . $currentDate .'-'. $newInvoiceNumberPadded;

        /********** */


        /********Request Data's */
        $staff_name = $request->staff_name;
        $notes = $request->notes;
        $entry_date = $request->entry_date;
        $approverName = $request->approverName;
        $checkedExpenses = $request->checkedExpenses;



        /******************* */

        /************geting totoal amount ******** */
        $checkedExpenses_string = implode(',', $checkedExpenses);

        $in = [$checkedExpenses_string];

        $totalamount = OtherExpenseSub::whereIn('mainid', $in)->sum('amount');

        /************************** */
        /**********
         * 
         * 
         * `id`, `ex_app_no`, `Staff_id`, `total_amount`, `note`, `hr_approval`, `hr_by`, `hr_date`, `ceo_approval`, `ceo_by`, `ceo_date`, `ho_approval`, `ho_by`, `ho_date`, `created_at`, `updated_at`
         * 
         * 
         */
        $ExpenseApp = new ExpensesApproval;
        $ExpenseApp->entry_date = $entry_date;
        $ExpenseApp->ex_app_no = $invoiceNumber;
        $ExpenseApp->Staff_id = $staff_name;
        $ExpenseApp->approver_id = $approverName;
        $ExpenseApp->note = $notes;
        $ExpenseApp->total_amount = $totalamount;
        $ExpenseApp->save();

        /************* get data to expense type */

        $lastInsertedId = $ExpenseApp->id;


        $UpdateStartToApp = OtherExpenses::whereIn('id', $checkedExpenses)->update([

            'expenses_app_id' => $lastInsertedId,

        ]);


        if ($lastInsertedId) {
            return response()->json([
                'status' => 200,
                'msg' => 'Added Sucessfuly'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'msg' => 'not insert'
            ]);
        }
    }

    public function popupsub(Request $request)
    {


        $main_id = $request->id;
/*************** heading ************************ */
        $get_staff = DB::table('other_expenses  as a')
            ->join('users as b', "a.executive_id", "b.id")
            ->select(
                'a.id',
                'a.entry_date',
                'b.userName',
                DB::raw("round((select sum(amount) from other_expense_subs where mainid=a.id ),2) as total_amount"),
            )
            ->where('a.id', $main_id)
            ->get();
            
            /***********Other Expenses ***** */
            $get_other = DB::table('other_expenses as a')
            ->leftjoin('other_expense_subs as b', 'a.id', '=', 'b.mainid')
            ->leftjoin('expense_types as c', 'b.expense_type_id', '=', 'c.id')
            ->select(
                'a.id',
                'a.entry_date',
                'c.expenseType',
                DB::raw('round(amount, 2) as other_amount')
            )
            ->where('a.id', $main_id)
            ->where('b.customer_id', null)
            ->where('b.call_no', null)
              ->get();
           

            //  $query = str_replace(array('?'), array('\'%s\''), $get_other->toSql());
            //      $query = vsprintf($query, $get_other->getBindings());

            //      echo $query;

if($get_staff){
    $st_status=200;
}
else{
    $st_status=400;
}

if($get_other){
    $ot_status=200;
}else{
    $ot_status=400;
}



        return response()->json([
            'st_status' => $st_status,
            'staff' => $get_staff,
            'ot_status' => $ot_status,
            'other_exp' => $get_other,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpensesApproval  $expensesApproval
     * @return \Illuminate\Http\Response
     */
    public function UpdateApproval(Request $request)
    {
        $main_id= $request->rowdata ;
        $approval_for=$request->action;

        $approve_status=$request->ApproveStatus;
       
        $token_id=$request->token;
      $currentDate=date('Y-m-d');
      $user = Token::where('tokenid', $token_id)->first();   
      $userid = $user['userid'];


      
      /***************
       * ho_approval
       * ho_by
       * ho_date
       * 
       * ceo_approval
       * ceo_by
       * ceo_date
       * 
       * hr_approval
       * hr_by
       * hr_date
       */
if($approval_for=='HOApprove'|| $approval_for=='HOApprove_reject'){
    $UpdateStartToApp = ExpensesApproval::where('id', $main_id)->update([

        'ho_approval' => $approve_status,
        'ho_by' => $userid,
        'ho_date' => $currentDate,
      
    ]);

}else if($approval_for=='CEOApprove'|| $approval_for=='CEOApprove_reject'){
    $UpdateStartToApp = ExpensesApproval::where('id', $main_id)->update([

        'ceo_approval' => $approve_status,
        'ceo_by' => $userid,
        'ceo_date' => $currentDate,
       
    ]);


}
else if($approval_for=='HRApprove' || $approval_for=='HRApprove_reject'){
    $UpdateStartToApp = ExpensesApproval::where('id', $main_id)->update([

        'hr_approval' => $approve_status,
        'hr_by' => $userid,
        'hr_date' => $currentDate,
        

    ]);

}

if ($UpdateStartToApp) {
   
       $status=200;
    
} else {
    $status=400;
}
return response()->json([
    'status' => $status,
]);
      

    }
    public function show(ExpensesApproval $expensesApproval)
    {

        //

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpensesApproval  $expensesApproval
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpensesApproval $expensesApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpensesApproval  $expensesApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpensesApproval $expensesApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpensesApproval  $expensesApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpensesApproval $expensesApproval)
    {
        //
    }

    public function PrintView(Request $request){
        $printId=$request->id;
         
          
          $exp_app = DB::table('expenses_approvals as a')
            ->join('users as b', 'a.Staff_id', '=', 'b.id')
            ->select('a.*', 'b.userName')
            ->where('a.id', $printId)
            ->first();
            
            $withCall = DB::table('expenses_approvals as a')
            ->leftJoin('other_expenses as b', 'b.expenses_app_id', '=', 'a.id')
            ->leftJoin('other_expense_subs as c', 'c.mainid', '=', 'b.id')
            ->leftJoin('call_log_creations as d', 'd.id', '=', 'c.call_no')
            ->leftJoin('customer_creation_profiles as e', 'e.id', '=', 'c.customer_id')
            ->select('a.ex_app_no', 'b.entry_date', 'd.callid', 'e.customer_name', 'c.amount')
            ->where('a.id', $printId)
            ->where('c.need_call_against_expense', '1')
            ->get();
        
            $otherExpense = DB::table('expenses_approvals as a')
            ->leftJoin('other_expenses as b', 'b.expenses_app_id', '=', 'a.id')
            ->leftJoin('other_expense_subs as c', 'c.mainid', '=', 'b.id')
            ->leftJoin('expense_types as d', 'd.id', '=', 'c.expense_type_id')
            ->select('a.ex_app_no', 'b.entry_date', 'b.expense_no', 'd.expenseType', 'c.description_sub', 'c.amount')
            ->where('a.id', $printId)
            ->where('c.need_call_against_expense', '0')
            ->get();
        
            return response()->json([
                'status' => 200,
                'exapp' => $exp_app,
                'withCall' =>$withCall,
                'otherExpense'=>$otherExpense,
            ]);   
            }

            public function ExpensesApprovalMaster(){
                $exp_app =  DB::table('expenses_approvals')->get();
                $accessor =[];
                
                $header =['Entry Date','Expense Bill No','Staff Name','Total Amount','HO Approval','CEO Approal','HR Approal',''];
                
                foreach($exp_app[0] as $key => $value ){
        
                    if($key === 'entry_date' || $key === 'ex_app_no' || $key === 'total_amount' || $key === 'hr_approval' || $key === 'ceo_approval' || $key === 'ho_approval')
                    {
                        $accessor[]=$key;
                    }
                        
                   }
                  
                if ($exp_app) {
                    return response()->json([
                        'status' => 200,
                        'data' => $exp_app,
                        'header'=> $header,
                        'title'=>'List Reimbursement Form',
                        'accessor'=>  $accessor,
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'No data'
                    ]);
                }
            }
}
