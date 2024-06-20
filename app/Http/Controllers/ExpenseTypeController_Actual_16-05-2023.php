<?php

namespace App\Http\Controllers;
use App\Models\Token;
use App\Models\ExpenseType;
use App\Models\ExpenseType_has_Limits;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        // return  $user;
        $userid = $user['userid'];

        if($user){
            $expenseType = new ExpenseType;
            $expenseType->expenseType = $request->expenseType;
            $expenseType->active_status = $request->activeStatus;
            $expenseType->created_userid = $userid;
            $expenseType->save();

            if($expenseType){
                foreach($request->limitOfRoles as $usertype => $limitOfRole){
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
                'message' => $request->expenseType.' Saved!',
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
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseType $expenseType)
    {
        //
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

        if($user){
            $expenseType = ExpenseType::find($id);
            $expenseType->expenseType = $request->expenseType;
            $expenseType->active_status = $request->activeStatus;
            $expenseType->edited_userid = $userid;
            $expenseType->save();

            if($expenseType){

                $deletesaved = ExpenseType_has_Limits::where('expnseType_id', $id)->delete();

                if($deletesaved){
                    foreach($request->limitOfRoles as $usertype => $limitOfRole){
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
                'message' => $request->expenseType.' Updated!',
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
    public function destroy( $id)
    {
        //
        try{
            $ExpenseType = ExpenseType::destroy($id);
            if($ExpenseType)    
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
    
    //Get
    public function getExpenseTypeList()
    {
        $expense_list = ExpenseType::where("active_status", "=", "active")->get();
        $expList = [];
        foreach($expense_list as $row){
            $expList[] = ["value" => $row['id'], "label" =>  $row['expenseType']] ;
        }
        return response()->json([
            'expenselist' =>  $expList,
        ]);
    }

}
