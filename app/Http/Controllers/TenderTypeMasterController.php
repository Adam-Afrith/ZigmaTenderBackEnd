<?php

namespace App\Http\Controllers;

use App\Models\TenderTypeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;

class TenderTypeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tenderType = TenderTypeMaster::orderBy('created_at', 'desc')->get();
        //->paginate(4)->appends(['sort'=>'ulb_name']);

        if ($tenderType)
            return response()->json([
                'status' => 200,
                'tenderType' => $tenderType
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

    public function store(Request $request)
    {
        
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $tendertype = TenderTypeMaster::where('tendertype', '=', $request->tendertype)->exists();
        if ($tendertype) {
            return response()->json([
                'status' => 400,
                'errors' => 'tendertype Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['tendertype' => 'required|string', 'tendertype_status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        $request->request->add(['cr_userid' => $user['userid']]);
        $request->request->remove('tokenId');
        
        $tendertype = TenderTypeMaster::firstOrCreate($request->all());
        if ($tendertype) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender Type Added Succssfully!'
            ]);
        }
        }
    }

    
    public function show($id)
    {

        $tendertype = TenderTypeMaster::find($id);
        if ($tendertype)
            return response()->json([
                'status' => 200,
                'tendertype' => $tendertype
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function edit(TenderTypeMaster $tenderTypeMaster)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $tenderType = TenderTypeMaster::where('tendertype', '=', $request->tendertype)
        ->where('id', '!=', $id)->exists();
        if ($tenderType) {
            return response()->json([
                'status' => 400,
                'errors' => 'TenderType Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(),
        ['tendertype' => 'required|string',
        'tendertype_status' => 'required' ,
        ]);
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');
        
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }



        $tenderType = TenderTypeMaster::findOrFail($id)->update($request->all());
        if ($tenderType)
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderTypeMaster  $tenderTypeMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            $tenderType = TenderTypeMaster::destroy($id);
            if($tenderType)
            {
                return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!",
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!?',
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


    public function getList(){
        $tendertypes = TenderTypeMaster::where("tendertype", "!=", "")
        ->where("tendertype_status","=","Active")
        ->get();

        $tendertypeList= [];
        foreach($tendertypes as $tendertype){
            $tendertypeList[] = ["value" => $tendertype['id'], "label" =>  $tendertype['tendertype']] ;
        }
        return  response()->json([
            'tendertypeList' =>  $tendertypeList,

        ]);
    }


   public function TenderTypesTable(Request $request)
   {
    $user = Token::where("tokenid", $request->tokenid)->first();
    if($user['userid'])
    {

    $header = ['TENDER TYPE NAME','STATUS'];
    $accessor = ['tendertype','tendertype_status'];

    $tenderType = TenderTypeMaster::orderBy('created_at', 'desc')->get(); 
    if ($tenderType)
        return response()->json([
            'status' => 200,
            'title' => 'TenderTypeMaster',
            'header' => $header,
            'accessor' => $accessor,
            'data' => $tenderType
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
