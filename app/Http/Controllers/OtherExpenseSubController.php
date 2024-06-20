<?php

namespace App\Http\Controllers;

use App\Models\OtherExpenseSub;
use App\Models\OtherExpenses;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class OtherExpenseSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $other_expense_sub = DB::table('other_expense_subs as oes')
					->join('expense_types as et','et.id','oes.expense_type_id')
					->select(
                            'oes.*',
                            // 'oes.id as oesid',
                            'et.id as etid',
                            'et.expenseType',
							// 'oes.amount',
                            // 'oes.description_sub',
                            // 'oes.originalfilename',
                            // 'oes.filesize',
                            // 'oes.hasfilename',
					)
					 ->get();
        if ($other_expense_sub)
            return response()->json([
                'status' => 200,
                'otherexpensesub' => $other_expense_sub
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();  
       // return "USER:".$user;
        $request->request->remove('tokenid');
        
        if($request ->hasFile('file')){
            $file = $request->file('file');
          
            $originalfileName = $file->getClientOriginalName();
            // $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hasfileName=$file->hashName();
            $fileType=$file->getMimeType();
            $filenameSplited=explode(".",$hasfileName);
         
                       
            if($filenameSplited[1]!=$originalfileName)
            {
            $fileName=$filenameSplited[0]."".$originalfileName;
            }
            else{
                $fileName=$hasfileName;
            }
            //$file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            $destinationPath = 'uploads/OtherExpenseSub/OtherExpSubFiles/';
            $result = $file->move($destinationPath, $hasfileName);
        }
           
            ///////////////////////////////////////////////////
            $expdate=explode("-",$request->entry_date);
            $curryear = substr($expdate[0],2,4);
            $currmonth = $expdate[1];
            $expseq_qry = OtherExpenses::select('expense_no')->where('entry_date','Like','%'.substr($expdate[0],2,2).'-'.$expdate[1].'%')->orderby('id', 'DESC')->limit(1)->get();
            
            $exp_id=null;
            if ($expseq_qry->isEmpty()) {
              
              $exp_id= "EX-" .substr($expdate[0],2,2).$expdate[1]. "00001";

            } else {
                 
                $year = substr($expseq_qry[0]->expense_no, 3, 2);
                $month = substr($expseq_qry[0]->expense_no, 5, 2);
                $lastid = substr($expseq_qry[0]->expense_no, 7, 5);
               
                if ($year == $curryear) {
                   
                    if ($month == $currmonth) {
                        
                        $exp_id= "EX-" . $curryear . $currmonth . str_pad(($lastid + 1), 5, '0', STR_PAD_LEFT);
 
                    
                       
                    } else {
                        
                        $exp_id= "EX-" . $curryear . $currmonth . "00001";
                        
                    }
                } 
                else 
                {
                    $exp_id= "EX-" . substr($expdate[0],2,2).$expdate[1]. "00001";
                }
                }
            //////////////////////////////////////////////////

//return "EXP No:".$exp_id;
            $get_id = OtherExpenses::where('entry_date',$request->entry_date)->where('executive_id',$request->executive_id)->get();
            $main_id = 0;
            if($get_id->isEmpty())
            { 
                    $otherExp = new OtherExpenses;
                    $otherExp->expense_no = $exp_id;
                    $otherExp->entry_date = $request->entry_date;
                    $otherExp->executive_id = $request->executive_id;
                    $otherExp->description = $request->description;
                    $otherExp->created_by = $user['userid'];
                    $otherExp->save();
                    $main_id = $otherExp->id;  
            }
            else
            {
            $main_id =$get_id[0]->id;
            }
                
                $otherExpenseSub = new OtherExpenseSub;
                $otherExpenseSub->mainid =$main_id;
                
                if(empty($request->customer_id))
                {
                    $otherExpenseSub->need_call_against_expense = 0;
                }
                else{
                    $otherExpenseSub->need_call_against_expense = 1;
                }   
                
                $otherExpenseSub->customer_id = $request->customer_id;
                $otherExpenseSub->call_no = $request->call_no;
                $otherExpenseSub->expense_type_id = $request->expense_type_id;
                $otherExpenseSub->amount = $request->amount;
                $otherExpenseSub->description_sub = $request->description_sub;
                $otherExpenseSub->originalfilename = $originalfileName;
                $otherExpenseSub->filetype = $fileType;
                $otherExpenseSub->filesize = $fileSize;
                $otherExpenseSub->hasfilename = $hasfileName;
                $otherExpenseSub->created_by = $user['userid'];
                $otherExpenseSub->need_call_against_expense=(!$request->customer_id->isEmpty() && $request->call_no->isEmpty()) ? 1 : 0; 
                $otherExpenseSub->save();
                
                // $otherExp = OtherExpenses::find($main_id);
                // $otherExp->description = $request->description;
                // $otherExp->save();
               
               // $otherExp->where('id',$request->id)->update(['description' => $request->description]);

                if($otherExpenseSub){
                    return response()->json([
                        'status' => 200,
                        'message' => 'Other Expense Created Successfully!'
                    ]);
                }
                
                else{
                    return response()->json([
                        'status' => 400,
                        'message' => 'Provided Credentials are Incorrect!'
                    ]);
                    }
            }
            // else
            // {
            //    // return "File isn't uploaded!!";
            //     ///////////////////////////////////////////////////
            // $expdate=explode("-",$request->entry_date);
            // $curryear = substr($expdate[0],2,4);
            // $currmonth = $expdate[1];
            // $expseq_qry = OtherExpenses::select('expense_no')->where('entry_date','Like','%'.substr($expdate[0],2,2).'-'.$expdate[1].'%')->orderby('id', 'DESC')->limit(1)->get();
            
            // $exp_id=null;
            // if ($expseq_qry->isEmpty()) {
              
            //   $exp_id= "EX-" .substr($expdate[0],2,2).$expdate[1]. "00001";

            // } else {
               
            //     $year = substr($expseq_qry[0]->expense_no, 3, 2);
            //     $month = substr($expseq_qry[0]->expense_no, 5, 2);
            //     $lastid = substr($expseq_qry[0]->expense_no, 7, 5);
               
            //     if ($year == $curryear) {
                   
            //         if ($month == $currmonth) {
                        
            //             $exp_id= "EX-" . $curryear . $currmonth . str_pad(($lastid + 1), 5, '0', STR_PAD_LEFT);
                       
                    
                       
            //         } else {
                        
            //             $exp_id= "EX-" . $curryear . $currmonth . "00001";
            //            // return "MeM:".$exp_id;
                        
            //         }
            //     } 
            //     else 
            //     {
            //         $exp_id= "EX-" . substr($expdate[0],2,2).$expdate[1]. "00001";
            //     }
            //     }
            // //////////////////////////////////////////////////
            // $get_id = OtherExpenses::where('entry_date',$request->entry_date)->where('executive_id',$request->executive_id)->get();
            // $main_id = 0;
            // if($get_id->isEmpty())
            // { 
            //         $otherExp = new OtherExpenses;
            //         $otherExp->expense_no = $exp_id;
            //         $otherExp->entry_date = $request->entry_date;
            //         $otherExp->executive_id = $request->executive_id;
            //         $otherExp->description = $request->description;
            //         $otherExp->created_by = $user['userid'];
            //         $otherExp->save();
            //         $main_id = $otherExp->id;
                       
            // }
            // else
            // {
            // $main_id =$get_id[0]->id;
            // }
                

            //     $otherExpenseSub = new OtherExpenseSub;
            //     $otherExpenseSub->mainid =$main_id;
            //     $otherExpenseSub->action = $request->action;
            //     $otherExpenseSub->customer_id = $request->customer_id;
            //     $otherExpenseSub->call_no = $request->call_no;
            //     $otherExpenseSub->expense_type_id = $request->expense_type_id;
            //     $otherExpenseSub->amount = $request->amount;
            //     $otherExpenseSub->description_sub = $request->description_sub;
            //     $otherExpenseSub->created_by = $user['userid'];
            //     $otherExpenseSub->save();

            //     $otherExp = OtherExpenses::find($main_id);
            //     $otherExp->description = $request->description;
            //     $otherExp->save();
                
                
            //     if($otherExpenseSub){
            //         return response()->json([
            //             'status' => 200,
            //             'message' => 'Other Expense Created Successfully!'
            //         ]);
            //     }
                
            //     else{
            //         return response()->json([
            //             'status' => 400,
            //             'message' => 'Provided Credentials are Incorrect!'
            //         ]);
            //         }
            // }
    // }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OtherExpenseSub  $otherExpenseSub
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $other_expense_sub = DB::table('other_expense_subs as oes')
					->join('expense_types as et','et.id','oes.expense_type_id')
					->select(
                            'oes.*',
                            'et.id as etid','et.expenseType',
							// 'oes.amount',
                            // 'oes.description_sub',
                            // "oes.filetype"
					)
                    ->where('oes.id',$id)
					->get();
        if ($other_expense_sub)
            return response()->json([
                'status' => 200,
                'otherexpensesub' => $other_expense_sub
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OtherExpenseSub  $otherExpenseSub
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherExpenseSub $otherExpenseSub)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OtherExpenseSub  $otherExpenseSub
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
          $user = Token::where('tokenid', $request->tokenid)->first(); 
         // return "TOKENSS:" .$user; 
          $request->request->add(['created_by' => $user['userid']]);
          if ($user['userid']) {
          $request->request->remove('tokenid');
    

            if($request ->hasFile('file')){
        
            $file = $request->file('file');
            $originalfileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hasfileName=$file->hashName();
            $filenameSplited=explode(".",$hasfileName);
           
                       
            if($filenameSplited[1]!=$originalfileName)
            {
            $fileName=$filenameSplited[0]."".$originalfileName;
            }
            else{
                $fileName=$hasfileName;
            }
            
            $data = OtherExpenseSub::where("id", "=", $id)->select("*")->get();
            
            $destinationPath = 'uploads/OtherExpenseSub/OtherExpSubFiles/'. $data[0]->hasfilename;

            unlink($destinationPath);
          
            $result = $file->move('uploads/OtherExpenseSub/OtherExpSubFiles/', $hasfileName);
            
               
                $request->request->add(['originalfilename' => $originalfileName]);
                $request->request->add(['filetype' => $fileType]);
                $request->request->add(['filesize' => $fileSize]);
                $request->request->add(['hasfilename' => $hasfileName]);

        }
    
        $otherExpense_sub_update = OtherExpenseSub::findOrFail($id)->update($request->all());

        if ($otherExpense_sub_update)
        {
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!",
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => "Sorry, Failed to Update, Try again later"
            ]);
        }
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OtherExpenseSub  $otherExpenseSub
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $other_expsub_del = OtherExpenseSub::destroy($id);
        if ($other_expsub_del)
            return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);

        else {
            return response()->json([
                'status' => 400,
                'message' => 'The Provided Credentials are Incorrect.'
            ]);
        }
    }

    public function download($fileName){

        $doc = OtherExpenseSub::find($fileName);
   
        if($doc){
            $fileName = $doc['hasfilename'];
            //$file = public_path()."'uploads/CallLogs/CallLogFiles/'".$fileName;
            $file = public_path('uploads/OtherExpenseSub/OtherExpSubFiles/'.$fileName);
            // return $file;
            return response()->download($file);
        }
    }

    

}
