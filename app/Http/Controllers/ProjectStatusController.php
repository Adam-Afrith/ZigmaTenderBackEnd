<?php

namespace App\Http\Controllers;

use App\Models\ProjectStatus;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $ProjectStatus = ProjectStatus::orderBy('created_at', 'desc')->get();
      
    
        if ($ProjectStatus)
            return response()->json([
                'status' => 200,
                'projectstatus' => $ProjectStatus
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
        $ProjectStatus = ProjectStatus::where('projectstatus', '=', $request->projectstatus)->exists();
        if ($ProjectStatus) {
            return response()->json([
                'status' => 400,
                'errors' => 'Project Status Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['projectstatus' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $ProjectStatus = ProjectStatus::firstOrCreate($request->all());
        if ($ProjectStatus) {
            return response()->json([
                'status' => 200,
                'message' => 'Project Status Has created Succssfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $ProjectStatus = ProjectStatus::find($id);
        if ($ProjectStatus)
            return response()->json([
                'status' => 200,
                'projectstatus' => $ProjectStatus
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
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectStatus $projectStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        // 
        $ProjectStatus = ProjectStatus::where('projectstatus', '=', $request->projectstatus)->where('id', '!=', $id)->exists();
        if ($ProjectStatus) {
            return response()->json([
                'status' => 400,
                'errors' => 'Project Status Already Exists'
            ]);
        }
        
        $validator = Validator::make($request->all(), ['projectstatus' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $ProjectStatus = ProjectStatus::findOrFail($id)->update($request->all());
        if ($ProjectStatus)
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
     * @param  \App\Models\ProjectStatus  $projectStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $ProjectStatus = ProjectStatus::destroy($id);
            if($ProjectStatus)    
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

    public function getList($profileid)
    {
        
        DB::enableQueryLog(); 

        $allprojectstatus = ProjectStatus::where("status", "=", "Active")
        // ->orWhere('id', function($query)  use ($profileid){
        //     $query->select('customer_sub_category')
        //     ->from(with(new CustomerCreationProfile)->getTable())
        //     ->where('id',$profileid);
        // })
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);
        
        $productstatusList = [];
        foreach($allprojectstatus as $projectstatus){
            $producttypeList[] = ["value" => $projectstatus['id'], "label" =>  $projectstatus['projectstatus']] ;
        }
        return  response()->json([
            'projectstatusList' =>  $producttypeList,
            'sqlquery' => $query
        ]);
    
    }

    public function ProjectStatusTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
        $header = ['Project Status','Status'];
        $accessor = ['projectstatus','status'];

        $ProjectStatus = ProjectStatus::orderBy('created_at', 'desc')->get();
        if ($ProjectStatus)
            return response()->json([
                'status' => 200,
                'title' => 'ProjectStatus',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $ProjectStatus
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
