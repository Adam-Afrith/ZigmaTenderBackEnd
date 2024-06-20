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

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ExpenseType = ExpenseType::orderBy('created_at', 'desc')->get();


        if ($ExpenseType)
            return response()->json([
                'status' => 200,
                'ExpenseType' => $ExpenseType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
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

    public function ExpInvoice()
    {

        /****invoice Genaration  */
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
    public function store(Request $request)
    {
        //      
        $user = Token::where('tokenid', $request->tokenid)->first();
        // return  $user;
        $userid = $user['userid'];

        if ($user) {
            $expenseType = new ExpenseType;
            $expenseType->expenseType = $request->expenseType;
            $expenseType->active_status = $request->activeStatus;
            $expenseType->created_userid = $userid;
            $expenseType->save();

            if ($expenseType) {
                foreach ($request->limitOfRoles as $usertype => $limitOfRole) {
                    $expenseType_has_limits = new ExpenseType_has_Limits;
                    $expenseType_has_limits->expnseType_id  = $expenseType['id'];
                    $expenseType_has_limits->userType_id    = $usertype;
                    $expenseType_has_limits->isUnlimited    = $limitOfRole['unlimited'];
                    $expenseType_has_limits->limit          = $limitOfRole['limit'];
                    $expenseType_has_limits->save();
                }
            }
        }

        if ($expenseType) {
            return response()->json([
                'status' => 200,
                'message' => $request->expenseType . ' Saved!',
                'id' => $expenseType['id'],
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }
    public function show($id)
    {
        //
        $ExpenseType = ExpenseType::with('limitsOfRole')->find($id);

        if ($ExpenseType)
            return response()->json([
                'status' => 200,
                'ExpenseType' => $ExpenseType
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
    public function edit(ExpenseType $expenseType)
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
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
    public function SubUpdate(Request $request)
    {

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

    public function subDel(Request $request, $id)
    {


        $deletesaved = OtherExpenseSub::where('id', $id)->delete();
        if ($deletesaved) {
            $status = 200;
        } else {
            $status = 400;
        }
        return response()->json([
            'status' => $status,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($user) {
            $expenseType = ExpenseType::find($id);
            $expenseType->expenseType = $request->expenseType;
            $expenseType->active_status = $request->activeStatus;
            $expenseType->edited_userid = $userid;
            $expenseType->save();

            if ($expenseType) {

                $deletesaved = ExpenseType_has_Limits::where('expnseType_id', $id)->delete();

                if ($deletesaved) {
                    foreach ($request->limitOfRoles as $usertype => $limitOfRole) {
                        $expenseType_has_limits = new ExpenseType_has_Limits;
                        $expenseType_has_limits->expnseType_id  = $expenseType['id'];
                        $expenseType_has_limits->userType_id    = $usertype;
                        $expenseType_has_limits->isUnlimited    = $limitOfRole['unlimited'];
                        $expenseType_has_limits->limit          = $limitOfRole['limit'];
                        $expenseType_has_limits->save();
                    }
                }
            }
        }


        if ($expenseType) {
            return response()->json([
                'status' => 200,
                'message' => $request->expenseType . ' Updated!',
                'id' => $expenseType['id'],
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $ExpenseType = ExpenseType::destroy($id);
            if ($ExpenseType) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => 404,
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

    //Get

    public function lmitAmount(Request $request)
    {
        $lmt_s = '';
        $lmt = '';
        $exp_type = $request->expenseType;
        $user_type = $request->userType;
        $get_limit = ExpenseType_has_Limits::where("expnseType_id", "=", $exp_type)
            ->where("userType_id", "=", $user_type)
            ->get(['isUnlimited', 'limit']);
        foreach ($get_limit as $limit) {
            $lmt_s = $limit->isUnlimited; // access the 'isUnlimited' value
            $lmt = $limit->limit; // access the 'limit' value
        }
        // foreach ($expense_list as $row) {
        //     $expList[] = ["value" => $row['id'], "label" =>  $row['expenseType']];
        // }
        if ($get_limit) {
            return response()->json([
                'status' => 200,
                'lmitsatatus' =>  $lmt_s,
                'lmitamt' =>  $lmt,
            ]);
        } else {
            return response()->json([
                'status' => 400,
            ]);
        }
    }
    public function getExpenseTypeList()
    {
        $expense_list = ExpenseType::where("active_status", "=", "active")->get();
        $expList = [];
        foreach ($expense_list as $row) {
            $expList[] = ["value" => $row['id'], "label" =>  $row['expenseType']];
        }
        return response()->json([
            'expenselist' =>  $expList,
        ]);
    }
    //Get
    public function customerNameList(Request $request)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($userid) {
            $customerList = CallLogCreation::where('executive_id', $userid)->groupby('customer_id')->get();

            $custlist = [];

            foreach ($customerList as $row) {

                $custdata = CustomerCreationProfile::where('id', $row['customer_id'])->first();
                $custlist[] = ["value" => $row['customer_id'], "label" =>  $custdata['customer_name']];
            }


            return response()->json([
                'customerlist' =>  $custlist
            ]);
        }
    }

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


    public function ExpanseTypeList(Request $request, $expid)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($userid) {

            $expList = [];
            $exptypelist = ExpenseType::select('expenseType')->where('id', $expid)->first();
            $exptypelimit =  ExpenseType_has_Limits::where('expnseType_id', $expid)->where('userType_id', $userid)->get();
            foreach ($exptypelimit as $row) {
                $id = $row['id'];
                $isUnlimited = $row['isUnlimited'];
                $limit = $row['limit'];
            }

            $expList[] = ['value' => $id, 'label' => $exptypelist['expenseType'], 'isUnlimited' => $isUnlimited, 'limit' => $limit];

            return response()->json([
                'expancetypelist' => $expList
            ]);
        }
    }



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


    public function finalSubmit(Request $request)
    {

        $UpdateOtherExpense = OtherExpenses::where('expense_no', $request->invc)->update([

            'description' => $request->description,
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
    public function EditSub(Request $request)
    {


        $get_sub = OtherExpenseSub::where('id', '=', $request->eidtId)->first();
        return response()->json([
            'status' => 200,
            'subdata' => $get_sub

        ]);
    }
    // public function Fileupload(Request $request,$id){

    //     $user = Token::where('tokenid', $request->tokenid)->first();
    //     $userid = $user['userid'];

    //     if( $userid && $request->hasFile('file')){

    //         $expanse = $request->file('file');
    //         $expanse_original = $expanse->getClientOriginalName();
    //         $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
    //         $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
    //         $expanse_mimeType =  $expanse->getMimeType();
    //         $expanse_filesize = ($expanse->getSize()) / 1000;


    //        $table = new CallFileSub;
    //        $table->mainid = $id;
    //        $table->originalfilename = $expanse_original;
    //        $table->filetype = $expanse_mimeType; 
    //        $table->filesize = $expanse_filesize;
    //        $table->hasfilename = $expanse_fileName;
    //        $table->createdby_userid = $userid;
    //        $table->save();


    //         return response()->json([
    //             'status'=>'File Uploaded Succssfully!'
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Invalid Credentials!'
    //         ]);
    //     }

    // }

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

    public function Expenseshow(Request $request, $id)
    {


        $expsublist = [];
        $explist = OtherExpenseSub::findOrFail($id);
        $exptype = ExpenseType::findOrFail($explist->expense_type_id);
        $doc = $explist->hasfilename;



        $fileName = $doc;
        //$file = public_path()."'uploads/CallLogs/CallLogFiles/'".$fileName;
        $link = public_path('uploads/Expensecreation/documentsupload/' . $fileName);
        // return $file;
        // $link = download($file);    


        $expsublist[] = ['id' => $explist->id, 'expense_type' => $exptype->expenseType, 'Amount' => $explist->amount, 'Description' => $explist->description_sub, 'Download' => url('api/downloadfile/' . $fileName)];

        return response()->json([

            'status' => $expsublist
        ]);
    }
    public function downloadFile($fileName)
    {
        $filePath = public_path('uploads/Expensecreation/documentsupload/' . $fileName);
        return Response()->download($filePath);
    }

    public function Expenseshowupdate(Request $request, $id)
    {

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if ($userid) {
            $expsublist[] = [];
            if ($request->hasFile('file')) {
                $document = OtherExpenseSub::find($id);
                $filename = $document['hasfilename'];
                $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
                // $file_path =  storage_path('app/public/BidDocs/'.$filename);

                if (File::exists($file_path)) {
                    if (File::delete($file_path)) {

                        $expanse = $request->file('file');
                        $expanse_original = $expanse->getClientOriginalName();
                        $expanse_fileName = intval(microtime(true) * 1000) . $expanse_original;
                        $expanse->storeAs('Expensecreation/documentsupload/', $expanse_fileName, 'public');
                        $expanse_mimeType =  $expanse->getMimeType();
                        $expanse_filesize = ($expanse->getSize()) / 1000;
                        $document->expense_type_id = $request->expense_type_id;
                        $document->amount = $request->amount;
                        $document->description_sub = $request->description_sub;
                        $document->originalfilename = $expanse_original;
                        $document->filetype = $expanse_mimeType;
                        $document->filesize = $expanse_filesize;
                        $document->hasfilename = $expanse_fileName;
                        $document->edited_by = $userid;
                        $document->save();

                        // $expsublist[] = ['id'=>$document->id,'expense_type'=>$document->expense_type_id,'amount   '=>$document->amount,'description'=>$document->description_sub,'Download'=>url('api/downloadfile/' . $document->hasfilename)];

                        return response()->json([
                            'status' => $document->id
                        ]);
                    }
                }
            }
        }
    }
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
    public function Expensedestroy($id)
    {
        try {
            $document = OtherExpenseSub::find($id);

            $filename = $document['hasfilename'];
            $file_path = public_path() . "/uploads/Expensecreation/documentsupload/" . $filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $doc = OtherExpenseSub::destroy($id);
            if ($doc) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            } else {
                return response()->json([
                    'status' => 404,
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

    public function get_staff_name_limits()
    {



        $get_staff = User:: join('expense_type_has__limits', 'users.userType', '=', 'expense_type_has__limits.userType_id')
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

    public function ExpenseTypeTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
        $header = ['ExpenseType Type','Status'];
        $accessor = ['expenseType','active_status'];

        $ExpenseType = ExpenseType::orderBy('created_at', 'desc')->get();
        if ($ExpenseType)
            return response()->json([
                'status' => 200,
                'title' => 'ExpenseType',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $ExpenseType
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
