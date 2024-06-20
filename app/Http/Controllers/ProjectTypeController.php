<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use App\Models\CustomerCreationProfile;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $projecttype = ProjectType::orderBy('created_at', 'desc')->get();
      
    
        if ($projecttype)
            return response()->json([
                'status' => 200,
                'projecttype' => $projecttype
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
        $ProjectType = ProjectType::where('projecttype', '=', $request->projecttype)->exists();
        if ($ProjectType) {
            return response()->json([
                'status' => 400,
                'errors' => 'Project Type Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['projecttype' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $ProjectType = ProjectType::firstOrCreate($request->all());
        if ($ProjectType) {
            return response()->json([
                'status' => 200,
                'message' => 'Project Type Has created Succssfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $projecttype = ProjectType::find($id);
        if ($projecttype)
            return response()->json([
                'status' => 200,
                'projecttype' => $projecttype
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
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectType $projectType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $ProjectType = ProjectType::where('projecttype', '=', $request->projecttype)
        ->where('id', '!=', $id)->exists();
        if ($ProjectType) {
            return response()->json([
                'status' => 400,
                'errors' => 'Project Type Already Exists'
            ]);
        }
        
        $validator = Validator::make($request->all(), ['projecttype' => 'required|string', 'status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $ProjectType = ProjectType::findOrFail($id)->update($request->all());
        if ($ProjectType)
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
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $ProjectType = ProjectType::destroy($id);
            if($ProjectType)    
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

        $projectypes = ProjectType::where("status", "=", "Active")
        // ->orWhere('id', function($query)  use ($profileid){
        //     $query->select('customer_sub_category')
        //     ->from(with(new CustomerCreationProfile)->getTable())
        //     ->where('id',$profileid);
        // })
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);
        
        $producttypeList = [];
        foreach($projectypes as $projectype){
            $producttypeList[] = ["value" => $projectype['id'], "label" =>  $projectype['projecttype']] ;
        }
        return  response()->json([
            'projectTypeList' =>  $producttypeList,
            'sqlquery' => $query
        ]);
    
    }

    public function getListofProjectType()
    {
        
        DB::enableQueryLog(); 

        $projectypes = ProjectType::where("status", "=", "Active")
        // ->orWhere('id', function($query)  use ($profileid){
        //     $query->select('customer_sub_category')
        //     ->from(with(new CustomerCreationProfile)->getTable())
        //     ->where('id',$profileid);
        // })
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);
        
        $producttypeList = [];
        foreach($projectypes as $projectype){
            $producttypeList[] = ["value" => $projectype['id'], "label" =>  $projectype['projecttype']] ;
        }
        return  response()->json([
            'projectTypeList' =>  $producttypeList,
            'sqlquery' => $query
        ]);
    
    }

    public function ProjectTypeTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {

        $header = ['Project Type','Status'];
        $accessor = ['projecttype','status'];
        
        $projecttype = ProjectType::orderBy('created_at', 'desc')->get();
        if ($projecttype)
            return response()->json([
                'status' => 200,
                'title' => 'ProjectType',
                'header' => $header,
                'accessor' => $accessor,
                'data' => $projecttype,
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
