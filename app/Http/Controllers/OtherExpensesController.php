<?php
namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\ExpenseType;
use App\Models\ExpenseType_has_Limits;
use App\Models\OtherExpenses;

use App\Models\OtherExpenseSub;
use App\Http\Controllers\Controller;
use App\Models\CallLogCreation;
use App\Models\CustomerCreationProfile;
use Illuminate\Http\Request;
use App\Models\CallFileSub;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class OtherExpensesController extends Controller
{
    
 /****invoice Genaration  */
 public function ExpInvoice()
 {

     $currentDate = date('Ym');
     $lastRecord = OtherExpenses::latest('expense_no')->first();

     if ($lastRecord) {
         $exp_rec = explode('-', $lastRecord->expense_no);
         $my = $exp_rec[1];

         if ($my == $currentDate) {
             $newInvoiceNumberPadded = str_pad($exp_rec[2] + 1, 4, '0', STR_PAD_LEFT);
             $invoiceNumber = 'EXP-' . $currentDate . '-' .  $newInvoiceNumberPadded;
         } else {

             $invoiceNumber = 'EXP-' . $currentDate . '-' . '0001';
         }
     } else {


         $invoiceNumber = 'EXP-' . $currentDate . '-' . '0001';
     }
     return response()->json([
         'status' => 200,
         'inv' => $invoiceNumber,

     ]);
 }
/*********Main List with filter function* */
 public function Mainlist(Request $request)
 {

     // $other_exapp = OtherExpenses::get();
     $fromdate = $request->fromdate;
     $todate = $request->todate;
     $excutive = $request->executive;

     $other_exapp = OtherExpenses::join('users', 'other_expenses.executive_id', '=', 'users.id')

         ->when($excutive, function ($query) use ($excutive) {

             return $query->where('other_expenses.executive_id', $excutive);
         })
         ->when($fromdate, function ($query) use ($fromdate, $todate) {
             return $query->whereBetween('other_expenses.entry_date', [$fromdate, $todate]);
         })


         ->get(['other_expenses.*', 'users.userName', DB::raw("round((select sum(amount) from other_expense_subs where  mainid=other_expenses.id ),2) as expense_amount")]);

     if ($other_exapp) {
         return response()->json([
             'status' => 200,
             'exp_app' => $other_exapp,
         ]);
     } else {
         return response()->json([
             'status' => 400,
             'message' => 'No data'
         ]);
     }
 }
/****Sub Eidt data sending Function */
public function GetDel(Request $request, $id)
{

 $other_exapp = OtherExpenses::where('id', '=', $id)->first();
 if ($other_exapp) {
     return response()->json([
         'status' => 200,
         'update_del' => $other_exapp,
     ]);
 } else {
     return response()->json([
         'status' => 400,
         'message' => 'No data'
     ]);
 }
}
/*****Sub data get and update funtion */
public function SubUpdate(Request $request)
{

    $expanse = $request->file('file');
   if($expanse!=''||$expanse!=null){
    $document = OtherExpenseSub::find($request->update_id);
    $filename = $document->hasfilename;
         
            $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);
        
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        
   
       
         if ($expanse) {
             $expanse_original = $expanse->getClientOriginalName();
             $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
             $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
             $expanse_mimeType =  $expanse->getMimeType();
             $expanse_filesize = ($expanse->getSize()) / 1000;
         } else {
    
             $expanse_original = '';
             $expanse_fileName = '';
    
             $expanse_mimeType =  '';
             $expanse_filesize = '';
         }
          

 $UpdateOtherExpenseSub = OtherExpenseSub::where('id', $request->update_id)->update([

     'expense_type_id' => $request->expense_type_id,
     'description_sub' => $request->description_sub,
     'amount' => $request->amount,
     'customer_id' => $request->customer_id,
     'call_no' => $request->call_no,
     'need_call_against_expense' => $request->need_call_against_expense,
     'originalfilename' => $expanse_original,
     'filetype' => $expanse_mimeType,
     'filesize' => $expanse_filesize,
     'hasfilename' => $expanse_fileName,
 ]);

 if ($UpdateOtherExpenseSub) {
    $status = 200;
} else {
    $status = 400;
}
return response()->json([
    'status' => $status,

]);


   }
   else if($request->removestatus){

    $document = OtherExpenseSub::find($request->update_id);
    $filename = $document->hasfilename;
         
            $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);
        
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        
   
       
         if ($expanse) {
             $expanse_original = $expanse->getClientOriginalName();
             $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
             $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
             $expanse_mimeType =  $expanse->getMimeType();
             $expanse_filesize = ($expanse->getSize()) / 1000;
         } else {
    
             $expanse_original = '';
             $expanse_fileName = '';
    
             $expanse_mimeType =  '';
             $expanse_filesize = '';
         }
          

 $UpdateOtherExpenseSub = OtherExpenseSub::where('id', $request->update_id)->update([

     'expense_type_id' => $request->expense_type_id,
     'description_sub' => $request->description_sub,
     'amount' => $request->amount,
     'customer_id' => $request->customer_id,
     'call_no' => $request->call_no,
     'need_call_against_expense' => $request->need_call_against_expense,
     'originalfilename' => $expanse_original,
     'filetype' => $expanse_mimeType,
     'filesize' => $expanse_filesize,
     'hasfilename' => $expanse_fileName,
 ]);

 if ($UpdateOtherExpenseSub) {
    $status = 200;
} else {
    $status = 400;
}
return response()->json([
    'status' => $status,

]);


   }
   else{
    $UpdateOtherExpenseSub = OtherExpenseSub::where('id', $request->update_id)->update([

        'expense_type_id' => $request->expense_type_id,
        'description_sub' => $request->description_sub,
        'amount' => $request->amount,
        'customer_id' => $request->customer_id,
        'call_no' => $request->call_no,
        'need_call_against_expense' => $request->need_call_against_expense,
    ]);


    if ($UpdateOtherExpenseSub) {
        $status = 200;
    } else {
        $status = 400;
    }
    return response()->json([
        'status' => $status,
    
    ]);
 }

 
}
/*******Sublit data sending **** */
public function ExpSub(Request $request)
 {

     if ($request->invc) {

         $expstatus = OtherExpenses::where('expense_no', '=', $request->invc)->exists();
         if ($expstatus) {
             $expid = OtherExpenses::where('expense_no', '=', $request->invc)
                 ->value('id');

             $get_sub = OtherExpenseSub::join('expense_types', 'expense_types.id', '=', 'other_expense_subs.expense_type_id')
                 ->where('mainid', $expid)
                 ->get(['other_expense_subs.*', 'expense_types.expenseType']);

             $row_count = $get_sub->count();
             // $get_sub=OtherExpenseSub::where('mainid','=',$expid)->get();
             if ($row_count > 0) {
                 return response()->json([
                     'status' => 200,
                     'sublist' => $get_sub
                 ]);
             } else {
                 return response()->json([
                     'status' => 400,

                 ]);
             }
         } else {

             return response()->json([
                 'status' => 400

             ]);
         }
     } else {
         return response()->json([
             'status' => 400

         ]);
     }
 }
/*** send data filed seting in edit */


public function EditSub(Request $request)
 {


     $get_sub = OtherExpenseSub::where('id', '=', $request->eidtId)->first();
     return response()->json([
         'status' => 200,
         'subdata' => $get_sub

     ]);
 }




/***OVERALL Submit Data */

public function finalSubmit(Request $request)
{

 $UpdateOtherExpense = OtherExpenses::where('expense_no', $request->invc)->update([

     'description' => $request->description,
     'executive_id' => $request->staffName,
     'entry_date' => $request->entryDate,
 ]);

 if ($UpdateOtherExpense) {
     $status = 200;
 } else {
     $status = 400;
 }
 return response()->json([
     'status' => $status,

 ]);
}

/**Sublit Add main Also Add  */

public function Expensestore(Request $request)
{


 $user = Token::where('tokenid', $request->tokenid)->first();
 $userid = $user['userid'];
 $expstatus = OtherExpenses::where('expense_no', '=', $request->invc)->exists();


 if ($expstatus) {

     // $validator = Validator::make(
     //     $request->all(),
     //     [
     //         'customer_id' => 'required',
     //         'call_no' => 'required',
     //         'expense_type_id' => 'required',
     //         'amount' => 'required',
     //         'description_sub' => 'required',
     //         'file' => 'required|file',
     //     ]
     // );
     // if ($validator->fails()) {
     //     return response()->json([
     //         'status' => 400,
     //         'errors' => $validator->messages(),
     //     ]);
     // }


     $expid = OtherExpenses::where('expense_no', '=', $request->invc)
         ->value('id');
     $expanse = $request->file('file');
     if ($expanse) {
         $expanse_original = $expanse->getClientOriginalName();
         $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
         $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
         $expanse_mimeType =  $expanse->getMimeType();
         $expanse_filesize = ($expanse->getSize()) / 1000;
     } else {

         $expanse_original = '';
         $expanse_fileName = '';

         $expanse_mimeType =  '';
         $expanse_filesize = '';
     }


     $table = new OtherExpenseSub;
     $table->mainid = $expid;
     $table->need_call_against_expense = $request->need_call_against_expense;
     $table->customer_id = $request->customer_id;
     $table->call_no = $request->call_no;
     $table->expense_type_id = $request->expense_type_id;
     $table->amount = $request->amount;
     $table->description_sub = $request->description_sub;
     $table->originalfilename = $expanse_original;
     $table->filetype = $expanse_mimeType;
     $table->filesize = $expanse_filesize;
     $table->hasfilename = $expanse_fileName;
     $table->created_by = $userid;
     $table->save();
     $newSubRow = OtherExpenseSub::find($table->id);

     $main_id = OtherExpenses::where('expense_no', $request->invc)->value('id');
     
     if ($table->id) {
         return response()->json([
             'status' => 200,
             'Mainid' => $main_id
         ]);
     } else {
         return response()->json([
             'status' => 400
         ]);
     }
 } else {


     $table = new OtherExpenses;
     $table->expense_no = $request->invc;
     $table->entry_date = $request->entry_date;
     $table->executive_id = $userid;
     $table->description = $request->description;
     $table->created_by = $userid;
     $table->save();

     $expanse = $request->file('file');
     if ($expanse) {
         $expanse_original = $expanse->getClientOriginalName();
         $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
         $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
         $expanse_mimeType =  $expanse->getMimeType();
         $expanse_filesize = ($expanse->getSize()) / 1000;
     } else {

         $expanse_original = '';
         $expanse_fileName = '';

         $expanse_mimeType =  '';
         $expanse_filesize = '';
     }

     $table2 = new OtherExpenseSub;
     $table2->mainid = $table->id;
     $table2->need_call_against_expense = $request->need_call_against_expense;
     $table2->customer_id = $request->customer_id;
     $table2->call_no = $request->call_no;
     $table2->expense_type_id = $request->expense_type_id;
     $table2->amount = $request->amount;
     $table2->description_sub = $request->description_sub;
     $table2->originalfilename = $expanse_original;
     $table2->filetype = $expanse_mimeType;
     $table2->filesize = $expanse_filesize;
     $table2->hasfilename = $expanse_fileName;
     $table2->created_by = $userid;
     $table2->save();
     $newSubRow = OtherExpenseSub::find($table2->mainid);
     $main_id = OtherExpenses::where('expense_no', $request->invc)->value('id');
     if ($table2->mainid) {
         return response()->json([
             'status' => 200,
             'Mainid' => $main_id
         ]);
     } else {
         return response()->json([
             'status' => 400
         ]);
     }
 }
}
/****dwonload  document function */
public function downloadFile(Request $request)
{
$res = OtherExpenseSub::where("id", $request->id)->where('originalfilename',$request->fileName)->first();
// return $res;
 $file_path = public_path('uploads/Expensecreation/documentsupload/' . $res->hasfilename);
 if(File::exists($file_path)) {
    return Response()->download($file_path);
}
 
}
/***Detele data in list */
public function deleteMain($id)
{

 $document = OtherExpenseSub::find($id);
 $documents = OtherExpenseSub::where('mainid', $id)->get();

 foreach ($documents as $document) {
     // access properties of $document
     $filename = $document->hasfilename;
     $filename = $document['hasfilename'];
     $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
     // $file_path =  storage_path('app/public/BidDocs/'.$filename);

     if (File::exists($file_path)) {
         File::delete($file_path);
     }
 }
 $des_sub = OtherExpenseSub::where('mainid', $id)->delete();

 $des_main = OtherExpenses::destroy($id);

 return response()->json([
     'status' => 200,
     'message' => 'Deleted Successfully!',
     "errormessage" => '',
 ]);
}

/***sub delete function */
public function Expensedestroy($id)
 {
     try {
         $document = OtherExpenseSub::find($id);

         $main_id = $document->mainid;
         $filename = $document->hasfilename;
         
         $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
         // $file_path =  storage_path('app/public/BidDocs/'.$filename);

         if (File::exists($file_path)) {
             File::delete($file_path);
         }

         $doc = OtherExpenseSub::destroy($id);

         $rowCount = OtherExpenseSub::where('mainid', $main_id)->count();
         if($rowCount==0){
             $doc = OtherExpenses::destroy($main_id);
             $status=300;
         }else{
             $status=200;
         }

         if ($doc) {
             return response()->json([
                 'status' => $status,
                 'message' => "Deleted Successfully!"
             ]);
         } else {
             return response()->json([
                 'status' => 400,
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
/****Staff name drop dwon  */

public function get_staff_name_limits()
 {

     $get_staff = User::leftjoin('expense_type_has__limits', 'users.userType', '=', 'expense_type_has__limits.userType_id')
     ->groupBy('users.id')
     ->get(['users.*', 'expense_type_has__limits.isUnlimited','expense_type_has__limits.limit']);


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
/****Customer list */
public function getList(){

 $countrys = CustomerCreationProfile::where("customer_name", "!=", "")->get();

 $customerList= [];
 foreach($countrys as $country){
     $customerList[] = ["value" => $country['id'], "label" =>  $country['customer_name']] ;
 }
 return  response()->json([
     'customerList' =>  $customerList,

 ]);
}
/*****Call Number sending   */
public function CallNumber(Request $request)
{

 $user = Token::where('tokenid', $request->tokenid)->first();
 $userid = $user['userid'];

 if ($userid) {

     // return $id;

     $callNumberList = CallLogCreation::where('executive_id', $userid)->where('customer_id', $request->id)->get();
     $callList = [];



     //  $query = str_replace(array('?'), array('\'%s\''), $get_other->toSql());
     //      $query = vsprintf($query, $get_other->getBindings());

     //      echo $query;

     foreach ($callNumberList as $row) {

         $callList[] = ['value' => $row['id'], 'label' => $row['callid']];
     }


     if (!empty($callList)) {

         return response()->json([
             'status' => 200,
             'CallList' =>  $callList
         ]);
     } else {

         return response()->json([
             'status' => 400,
             'msg' =>  'No Calls For This Customer',
         ]);
     }
 }
}
/*****Get limit amount  */

public function lmitAmount(Request $request)
{
 $lmt_s='';
 $lmt='';
 $exp_type=$request->expenseType;
 $user_type=$request->userType;
 $get_limit = ExpenseType_has_Limits::where("expnseType_id", "=", $exp_type)
     ->where("userType_id", "=", $user_type)
     ->get(['isUnlimited','limit']);
     foreach($get_limit as $limit) {
         $lmt_s= $limit->isUnlimited; // access the 'isUnlimited' value
         $lmt= $limit->limit; // access the 'limit' value
     }
 // foreach ($expense_list as $row) {
 //     $expList[] = ["value" => $row['id'], "label" =>  $row['expenseType']];
 // }
 if($get_limit){
     return response()->json([
         'status'=>200,
         'lmitsatatus' =>  $lmt_s,
         'lmitamt' =>  $lmt,
     ]);
 }else{
     return response()->json([
         'status'=>400,
     ]);

 }
 
}

public function OtherExpTable(Request $request)
{
    $user = Token::where("tokenid", $request->tokenid)->first();
    if($user['userid'])
    {
        $header = ['Entry Date','Expense No','Branch Name / Staff Name','Total Amount','View'];
        $accessor = ['entry_date','expense_no','userName','expense_amount','view'];

        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $excutive = $request->executive;
   
        $other_exapp = OtherExpenses::join('users', 'other_expenses.executive_id', '=', 'users.id')
   
            ->when($excutive, function ($query) use ($excutive) {
   
                return $query->where('other_expenses.executive_id', $excutive);
            })
            ->when($fromdate, function ($query) use ($fromdate, $todate) {
                return $query->whereBetween('other_expenses.entry_date', [$fromdate, $todate]);
            })
   
   
            ->get(['other_expenses.*', 'users.userName', DB::raw("round((select sum(amount) from other_expense_subs where  mainid=other_expenses.id ),2) as expense_amount")]);
   
        if ($other_exapp) {
            return response()->json([
                'status' => 200,
                'title' => 'OtherExpenses',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $other_exapp,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No data'
            ]);
        }
    }

}

}
