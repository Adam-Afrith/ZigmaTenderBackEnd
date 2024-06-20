<?php

namespace App\Http\Controllers;

use App\Models\BidManagementTenderOrBidStaus;
use App\Models\Token;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;

class BidManagementTenderOrBidStausController extends Controller
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
                $file->storeAs('BidManagement/tenderstatus_status', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }

            $BidManagementTenderOrBidStaus = new BidManagementTenderOrBidStaus;
            $BidManagementTenderOrBidStaus->bidid  = $request->bidCreationMainId;
            $BidManagementTenderOrBidStaus->status = $request->tenderstatus;
            $BidManagementTenderOrBidStaus->created_by = $userid;
            if($request ->hasFile('file')){
                $BidManagementTenderOrBidStaus -> file_original_name = $filename_original;
                $BidManagementTenderOrBidStaus -> file_new_name = $fileName;
                $BidManagementTenderOrBidStaus -> file_type = $mimeType;
                $BidManagementTenderOrBidStaus -> file_size = $filesize;
                $BidManagementTenderOrBidStaus -> ext = $ext;
            }

            $BidManagementTenderOrBidStaus ->save();
        }

        if ($BidManagementTenderOrBidStaus) {
            return response()->json([
                'status' => 200,
                'message' => 'Submitted Successfully!',
                'id' => $BidManagementTenderOrBidStaus['id'],
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
     * @param  \App\Models\BidManagementTenderOrBidStaus  $bidManagementTenderOrBidStaus
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         //
         $BidManagementTenderOrBidStaus = BidManagementTenderOrBidStaus::where('bidid',$id)->get();

         if (count($BidManagementTenderOrBidStaus) > 0){
 
             $filename = $BidManagementTenderOrBidStaus[0]['file_new_name'];
 
             return response()->json([
                 'status' => 200,
                 'BidManagementTenderOrBidStaus' => $BidManagementTenderOrBidStaus[0],
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


    public function getdocs($id){

        $doc = BidManagementTenderOrBidStaus::find($id);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/tenderstatus_status/".$filename;
            if(file_exists($file)){
                 return response()->download($file);
            }else{
                return response()->json([
                    'status' => 'error',
                ], 204);
            }
        }else{
            return response()->json([
                'status' => 'error',
            ], 204);
        }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidManagementTenderOrBidStaus  $bidManagementTenderOrBidStaus
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementTenderOrBidStaus $bidManagementTenderOrBidStaus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementTenderOrBidStaus  $bidManagementTenderOrBidStaus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $document = BidManagementTenderOrBidStaus::find($id);
        $filename = $document['file_new_name'];

        if($filename){
            $file_path = public_path()."/uploads/BidManagement/tenderstatus_status/".$filename;
            if(File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

        if($user){
            if($request ->hasFile('file')){
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('BidManagement/tenderstatus_status', $fileName, 'public');
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

            $BidManagementTenderOrBidStaus = BidManagementTenderOrBidStaus::findOrFail($id)->update([
                'status' => $request->tenderstatus,
                'file_original_name' => $filename_original,
                'file_new_name' => $fileName,
                'file_type' => $mimeType,
                'file_size' => $filesize,
                'ext' => $ext,
                'edited_by'=> $userid
            ]);
        }

        if($BidManagementTenderOrBidStaus) {
            return response()->json([
                'status' => 200,
                'message' => 'Updated Succssfully!',
                // 'id' => $tenderFee['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to Update!',                                         
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementTenderOrBidStaus  $bidManagementTenderOrBidStaus
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidManagementTenderOrBidStaus $bidManagementTenderOrBidStaus)
    {
        //
    }
}
