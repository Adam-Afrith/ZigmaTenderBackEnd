<?php

namespace App\Http\Controllers;

use App\Models\BidmanagementPreBidQueries;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\File;

class BidmanagementPreBidQueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request ->hasFile('file')){
            $file = $request->file('file');
            $filename_original = $file->getClientOriginalName();
            $fileName =intval(microtime(true) * 1000) . $filename_original;
            $file->storeAs('BidManagement/prebidqueries', $fileName, 'public');
            $mimeType =  $file->getMimeType();
            $filesize = ($file->getSize())/1000;
            $ext =  $file->extension();

            $user = Token::where('tokenid', $request->tokenid)->first();   
            $userid = $user['userid'];

            if($user){
                $BidCreation = new BidmanagementPreBidQueries;
                $BidCreation -> date = $request->date ;
                $BidCreation -> file_original_name = $filename_original ;
                $BidCreation -> file_new_name = $fileName ;
                $BidCreation -> bidCreationMainId = $request -> bid_creation_mainid ;
                $BidCreation -> file_type = $mimeType ;
                $BidCreation -> file_size =  $filesize;
                $BidCreation -> ext =  $ext;
                $BidCreation -> createdby_userid = $userid ;
                $BidCreation -> updatedby_userid = 0 ;
                $BidCreation -> save();
            }

            if ($BidCreation) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Uploaded Succcessfully'
                ]);
            }else{
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
     * @param  \App\Models\BidmanagementPreBidQueries  $bidmanagementPreBidQueries
     * @return \Illuminate\Http\Response
     */
    public function show(BidmanagementPreBidQueries $bidmanagementPreBidQueries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidmanagementPreBidQueries  $bidmanagementPreBidQueries
     * @return \Illuminate\Http\Response
     */
    public function edit(BidmanagementPreBidQueries $bidmanagementPreBidQueries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidmanagementPreBidQueries  $bidmanagementPreBidQueries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $BidCreation = null;
       
        if($request ->hasFile('file')){
            $document = BidmanagementPreBidQueries::find($id);
            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/prebidqueries/".$filename;
       
            
            if(File::exists($file_path)) {
                if(File::delete($file_path)){
          
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName =intval(microtime(true) * 1000) . $filename_original;
                    $file->storeAs('BidManagement/prebidqueries', $fileName, 'public');
                    $mimeType =  $file->getMimeType();
                    $filesize = ($file->getSize())/1000;
                    $ext =  $file->extension();

                    $user = Token::where('tokenid', $request->tokenid)->first();   
                    $userid = $user['userid'];

                    if($userid){
                        $BidCreation = BidmanagementPreBidQueries::find($id);
                        $BidCreation -> date = $request->date ;
                        $BidCreation -> file_original_name = $filename_original ;
                        $BidCreation -> file_new_name = $fileName ;
                        $BidCreation -> bidCreationMainId = $request -> bid_creation_mainid ;
                        $BidCreation -> file_type = $mimeType ;
                        $BidCreation -> file_size =  $filesize;
                        $BidCreation -> ext =  $ext;
                        $BidCreation -> updatedby_userid =  $userid ;
                        $BidCreation -> save();
                    }

                }
            }
        }

        if ($BidCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Updated Succcessfully'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to update!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidmanagementPreBidQueries  $bidmanagementPreBidQueries
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $document = BidmanagementPreBidQueries::find($id);

            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/prebidqueries/".$filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if(File::exists($file_path)) {
                File::delete($file_path);
            }

            $doc = BidmanagementPreBidQueries::destroy($id);
            if($doc)    
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

    public function getUplodedDocList(Request $request){
        $docs = DB::table('bidmanagement_pre_bid_queries')
        ->where('bidCreationMainId', $request->mainid)
        ->select('*') 
        ->orderBy('id', 'desc')       
        ->get();
    
        if ($docs)
            return response()->json([
                'status' => 200,
                'docs' => $docs
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($fileName){

        $doc = BidmanagementPreBidQueries::find($fileName);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/prebidqueries/".$filename;
            return response()->download($file);
        }

    }
}
