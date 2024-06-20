<?php

namespace App\Http\Controllers;

use App\Models\BidCreationBidSubmittedStatus;
use Illuminate\Http\Request;
use App\Models\Token;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;

class BidCreationBidSubmittedStatusController extends Controller
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
        //get the user id 
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($userid){

            if($request ->hasFile('file')){
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('BidManagement/bidsubmittedstatus', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }

            $BidCreationBidSubmittedStatus = new BidCreationBidSubmittedStatus;
            $BidCreationBidSubmittedStatus -> bidSubmittedStatus = $request->bidSubmittedStatus;
            $BidCreationBidSubmittedStatus -> modeofsubmission = $request->modeofsubmission;
            $BidCreationBidSubmittedStatus -> bidCreationMainId = $request->bidCreationMainId;
            $BidCreationBidSubmittedStatus -> createdby_userid = $userid;
            $BidCreationBidSubmittedStatus -> updatedby_userid = 0;
            if($request ->hasFile('file')){
                $BidCreationBidSubmittedStatus -> file_original_name = $filename_original;
                $BidCreationBidSubmittedStatus -> file_new_name = $fileName;
                $BidCreationBidSubmittedStatus -> file_type = $mimeType;
                $BidCreationBidSubmittedStatus -> file_size = $filesize;
                $BidCreationBidSubmittedStatus -> ext = $ext;
            }
            $BidCreationBidSubmittedStatus ->save();

        }
        
        if ($BidCreationBidSubmittedStatus) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender fee Saved Succssfully!',
                'id' => $BidCreationBidSubmittedStatus['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidCreationBidSubmittedStatus  $bidCreationBidSubmittedStatus
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         //
         $BidCreationBidSubmittedStatus = BidCreationBidSubmittedStatus::where('bidCreationMainId',$id)->get();

         if (count($BidCreationBidSubmittedStatus) > 0){
 
             $filename = $BidCreationBidSubmittedStatus[0]['file_new_name'];
 
             return response()->json([
                 'status' => 200,
                 'BidCreationBidSubmittedStatus' => $BidCreationBidSubmittedStatus[0],
                 'file' =>  $filename
             ]);
         }
 
         else {
             return response()->json([
                 'status' => 404,
                 'message' => 'The provided credentials are Invalid'
             ]);
         }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidCreationBidSubmittedStatus  $bidCreationBidSubmittedStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreationBidSubmittedStatus $bidCreationBidSubmittedStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreationBidSubmittedStatus  $bidCreationBidSubmittedStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $tenderFee = null;
        $document = BidCreationBidSubmittedStatus::find($id);
        $filename = $document['file_new_name'];
      
        if($filename){
            $file_path = public_path()."/uploads/BidManagement/bidsubmittedstatus/".$filename;
            if(File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        //get the user id 
        DB::enableQueryLog();
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);

        if($user ){

            if($request ->hasFile('file')){
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('BidManagement/bidsubmittedstatus', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }else{
                $filename_original = '';
                $fileName= '';
                $mimeType= '';
                $filesize= 0;
                $ext='';
            }


            $BidCreationBidSubmittedStatus = BidCreationBidSubmittedStatus::findOrFail($id)->update([

                'bidSubmittedStatus' => $request->bidSubmittedStatus,
                'modeofsubmission' => $request->modeofsubmission,
                'file_original_name' => $filename_original,
                'file_new_name' => $fileName,
                'file_type' => $mimeType,
                'file_size' => $filesize,
                'ext' => $ext,
                'updatedby_userid'=> $userid

                
            ]);

          
        }

        if($BidCreationBidSubmittedStatus) {
            return response()->json([
                'status' => 200,
                'message' => 'Bid Submitted Status Updated Succssfully!',
                // 'id' => $tenderFee['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!',
                                                                         
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidCreationBidSubmittedStatus  $bidCreationBidSubmittedStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidCreationBidSubmittedStatus $bidCreationBidSubmittedStatus)
    {
        //
    }

    public function getdocs($id){

        $doc = BidCreationBidSubmittedStatus::find($id);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/bidsubmittedstatus/".$filename;
            return response()->download($file);
        }

    }
}
