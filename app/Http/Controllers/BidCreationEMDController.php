<?php

namespace App\Http\Controllers;

use App\Models\BidCreationEMD;
use Illuminate\Http\Request;
use App\Models\Token;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;

class BidCreationEMDController extends Controller
{
    
    public function index()
    {
        
    }

  
    public function create()
    {
       
    }

  
    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        if($userid){

            if($request ->hasFile('file')){
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName =intval(microtime(true) * 1000) . $filename_original;
                $file->storeAs('BidManagement/tenderfee', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }

            $tenderEmd = new BidCreationEMD;
            $tenderEmd -> bankname = $request->bankname;
            $tenderEmd -> bankbranch = $request->bankbranch;
            $tenderEmd -> mode = $request->mode;
            $tenderEmd -> dateofsubmission = $request->dateofsubmission;
            $tenderEmd -> bgno = $request->bgno;
            $tenderEmd -> ddno = $request->ddno;
            $tenderEmd -> utrno = $request->utrno;
            $tenderEmd -> dateofissue = $request->dateofissue;
            $tenderEmd -> expiaryDate = $request->expiaryDate;
            $tenderEmd -> refno = $request->refno;
            $tenderEmd -> dateofpayment = $request->dateofpayment;
            $tenderEmd -> value = $request->value;
            $tenderEmd -> bidCreationMainId = $request->bidCreationMainId;
            $tenderEmd -> createdby_userid = $userid;
            $tenderEmd -> updatedby_userid = 0;
            if($request ->hasFile('file')){
                $tenderEmd -> file_original_name = $filename_original;
                $tenderEmd -> file_new_name = $fileName;
                $tenderEmd -> file_type = $mimeType;
                $tenderEmd -> file_size = $filesize;
                $tenderEmd -> ext = $ext;
            }
            $tenderEmd ->save();

          
        }

        if ($tenderEmd) {
            return response()->json([
                'status' => 200,
                'message' => 'EMD Fee Saved Succssfully!',
                'id' => $tenderEmd['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    public function show($id)
    {
        //
        $BidCreationEMD = BidCreationEMD::where('bidCreationMainId',$id)->get();

        if (count($BidCreationEMD) > 0){

            $filename = $BidCreationEMD[0]['file_new_name'];
            // $file = public_path()."/uploads/BidManagement/tenderfee/".$filename;

            return response()->json([
                'status' => 200,
                'BidCreationEMD' => $BidCreationEMD[0],
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

    
    public function edit(BidCreationEMD $bidCreationTenderFee)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
    
        $tenderEmd = null;
        $document = BidCreationEMD::find($id);
        $filename = $document['file_new_name'];
      
        if($filename){
            $file_path = public_path()."/uploads/BidManagement/tenderfee/".$filename;
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
                $file->storeAs('BidManagement/tenderfee', $fileName, 'public');
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

         

            $tenderEmd = BidCreationEMD::findOrFail($id)->update([

                'bankname' => $request -> bankname,
                'bankbranch' => $request -> bankbranch,
                'mode' => $request -> mode,
                'dateofsubmission' => $request -> dateofsubmission,
                'bgno' => $request -> bgno,
                'ddno' => $request -> ddno,
                'utrno' => $request -> utrno,
                'dateofissue' => $request -> dateofissue,
                'expiaryDate' => $request -> expiaryDate,
                'refno' => $request -> refno,
                'dateofpayment' => $request -> dateofpayment,
                'value' => $request -> value,
                'file_original_name' => $filename_original,
                'file_new_name' => $fileName,
                'file_type' => $mimeType,
                'file_size' => $filesize,
                'ext' => $ext,
                'updatedby_userid'=> $userid

                
            ]);

          
        }

        if($tenderEmd) {
            return response()->json([
                'status' => 200,
                'message' => 'EMD Fee Updated Succssfully!',
                // 'id' => $tenderEmd['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!',                                                              
            ]);
        }

    }

    public function destroy(BidCreationEMD $bidCreationTenderFee)
    {
        //
    }

    public function getdocs($id){

        $doc = BidCreationEMD::find($id);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/tenderfee/".$filename;
            return response()->download($file);
        }
    }
}