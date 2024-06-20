<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubCategory;
use App\Models\CustomerCreationProfile;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $CustomerSubCategory = CustomerSubCategory::orderBy('created_at', 'desc')->get();
      
    
        if ($CustomerSubCategory)
            return response()->json([
                'status' => 200,
                'customersubcategory' => $CustomerSubCategory
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
        $CustomerSubCategory = CustomerSubCategory::where('customersubcategory', '=', $request->customersubcategory)->exists();
        if ($CustomerSubCategory) {
            return response()->json([
                'status' => 400,
                'errors' => 'Customer Subcategoty Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['customersubcategory' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $CustomerSubCategory = CustomerSubCategory::firstOrCreate($request->all());
        if ($CustomerSubCategory) {
            return response()->json([
                'status' => 200,
                'message' => 'Customer Category Has created Succssfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerSubCategory  $customerSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $CustomerSubCategory = CustomerSubCategory::find($id);
        if ($CustomerSubCategory)
            return response()->json([
                'status' => 200,
                'customersubcategory' => $CustomerSubCategory
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
     * @param  \App\Models\CustomerSubCategory  $customerSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerSubCategory $customerSubCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerSubCategory  $customerSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $CustomerSubCategory = CustomerSubCategory::where('customersubcategory', '=', $request->customersubcategory)
        ->where('id', '!=', $id)->exists();
        if ($CustomerSubCategory) {
            return response()->json([
                'status' => 400,
                'errors' => 'Customer Sub Category Already Exists'
            ]);
        }
        
        $validator = Validator::make($request->all(), ['customersubcategory' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $CustomerSubCategory = CustomerSubCategory::findOrFail($id)->update($request->all());
        if ($CustomerSubCategory)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerSubCategory  $customerSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $CustomerSubCategory = CustomerSubCategory::destroy($id);
            if($CustomerSubCategory)    
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

    public function getList($profileid){
        
        DB::enableQueryLog(); 

        $CustomerSubCategorys = CustomerSubCategory::where("status", "=", "Active")
        ->orWhere('id', function($query)  use ($profileid){
            $query->select('customer_sub_category')
            ->from(with(new CustomerCreationProfile)->getTable())
            ->where('id',$profileid);
        })
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);
        
        $CustomerSubCategoryList = [];
        foreach($CustomerSubCategorys as $CustomerSubCategory){
            $CustomerSubCategoryList[] = ["value" => $CustomerSubCategory['id'], "label" =>  $CustomerSubCategory['customersubcategory']] ;
        }
        return  response()->json([
            'CustomerSubCategoryList' =>  $CustomerSubCategoryList,
            'sqlquery' => $query
        ]);
    }

    public function CustSubCatTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {

        $header = ['Customer Sub Category','Status'];
        $accessor = ['customersubcategory','status'];

        $CustomerSubCategory = CustomerSubCategory::orderBy('created_at', 'desc')->get();
        if ($CustomerSubCategory)
            return response()->json([
                'status' => 200,
                'title' => 'CustomerSubCategory',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $CustomerSubCategory
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
