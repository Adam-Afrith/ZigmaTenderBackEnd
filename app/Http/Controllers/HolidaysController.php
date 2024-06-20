<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $holidaylist = []; 
        $holiday_data = Holiday::orderBy('created_at', 'desc')->get();

        if ($holiday_data)
        {
         
        foreach($holiday_data as $row)
        {   
            // $role = DB::table('model_has_roles as m')
            // ->Join('roles as r', 'r.id', '=', 'm.role_id')
            // ->where('m.model_id', $row->created_by)
            // ->select('r.id as id','r.name as name')
            // ->first();
           
            // if($role->id !=1 && $role->name != 'Admin')
            // {
                
                $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks' => $row->remarks]; 

            // }
           
        }
      
                return response()->json([
                'status' => 200,
                'holidaylist' => $holidaylist
            ]);
        }
        else
         {
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
        $validator = Validator::make($request->all(), [
            'date' => ['required', 'date', 'unique_holiday_date'],
            'occasion' => 'required',
           
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 422);
        }

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        if($user)
        {
                $table = new Holiday;
                $table->date = date('Y-m-d', strtotime($request->date));
                $table->occasion = $request->occasion;
                $table->remarks = $request->remarks;
                $table->created_by = $userid; 
                $table->save();

                
                    try 
                    {
                        $table->saveOrFail();
                        return response()->json([
                            'status' => 'Saved!'
                        ]);
                    } catch (\Exception $e) 
                    {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Unable to save!'
                        ]);
                    }

                


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $holidaylist = [];
        $holiday = Holiday::find($id);
        if($holiday)
        {

            $role = DB::table('model_has_roles as m')
            ->Join('roles as r', 'r.id', '=', 'm.role_id')
            ->where('m.model_id', $holiday->created_by)
            ->select('r.id as id','r.name as name')
            ->first();
           
            // if($role->id !=1 && $role->name != 'Admin')
            // {
                $holidaylist[] = ['id'=>$holiday->id,'date'=>$holiday->date,'occasion'=>$holiday->occasion, 'remarks' => $holiday->remarks];
                return response()->json([
                    'status' => 200,
                    'holidaylist' => $holidaylist
                ]);
            // }
        }
        else 
        {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'occasion' => 'required',
           
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 422);
        }

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        if($user)
        {
            $table = Holiday::find($id);
            $table->date = date('Y-m-d', strtotime($request->date));
            $table->occasion = $request->occasion;
            $table->remarks = $request->remarks;
            $table->created_by = $userid;
            $table->edited_by = $userid; 
            $table->save();
            if($table)
            {
                return response()->json([
                    'last inserted id'=>$table->id
                ]);

            }
            

        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $holiday = Holiday::destroy($id);
            if($holiday)    
            {return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.',
               
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


        public function HolidayMasterTable()
        {
            
            $header=['Occasion','Date','Remarks','Action'];
            $accessor=['date','occasion','remarks','action'];
            $holidaylist = []; 
            $holiday_data = Holiday::orderBy('created_at', 'desc')->get();
    
            if ($holiday_data)
            {
             
            foreach($holiday_data as $row)
            {   
                    $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks' => $row->remarks]; 
            }
          
                    return response()->json([
                    'status' => 200,
                    'title' => 'HolidayMaster',
                    'header' => $header,
                    'accessor' => $accessor,
                    'data' => $holidaylist,
                  
                ]);
            }
            else
             {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
             }
        }

        public function HolidayMaster(Request $request)
        {
            $holidaylist = []; 
            $holiday_data = Holiday::orderBy('created_at', 'desc')->get();
            $accessor =[];
            $header=['Occasion','Date','Remarks'];
            if ($holiday_data)
            {
    
            foreach($holiday_data as $row)
            {
                $role = DB::table('model_has_roles as m')
                ->Join('roles as r', 'r.id', '=', 'm.role_id')
                ->where('m.model_id', $row->created_by)
                ->select('r.id as id','r.name as name')
                ->first();
               
                if($role->id !=1 && $role->name != 'Admin')
                {
    
                    $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks'=>$row->remarks]; 
    
                }
               
            }
    
            foreach($holidaylist[0] as $key => $value ){
    
                if($key === 'occasion' || $key === 'date' || $key === 'remarks'){
                    $accessor[]=$key;
                }
                   
               }
                    return response()->json([
                    'status' => 200,
                    'data' => $holidaylist,
                    'header'=> $header,
                    'title'=>'Holiday Master',
                    'accessor'=>  $accessor,
                ]);
            }
            else
             {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
             }
        }
}
