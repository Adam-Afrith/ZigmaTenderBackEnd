<?php

namespace App\Http\Controllers;

use App\Models\BidCreationTenderFee;
use Illuminate\Http\Request;
use App\Models\Token;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;


class BidCreationTenderFeeController extends Controller
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
                $file->storeAs('BidManagement/tenderfee', $fileName, 'public');
                $mimeType =  $file->getMimeType();
                $filesize = ($file->getSize())/1000;
                $ext =  $file->extension();
            }

            $tenderFee = new BidCreationTenderFee;
            $tenderFee -> bankname = $request->bankname;
            $tenderFee -> bankbranch = $request->bankbranch;
            $tenderFee -> mode = $request->mode;
            $tenderFee -> dateofsubmission = $request->dateofsubmission;
            $tenderFee -> bgno = $request->bgno;
            $tenderFee -> ddno = $request->ddno;
            $tenderFee -> utrno = $request->utrno;
            $tenderFee -> dateofissue = $request->dateofissue;
            $tenderFee -> expiaryDate = $request->expiaryDate;
            $tenderFee -> refno = $request->refno;
            $tenderFee -> dateofpayment = $request->dateofpayment;
            $tenderFee -> value = $request->value;
            $tenderFee -> bidCreationMainId = $request->bidCreationMainId;
            $tenderFee -> createdby_userid = $userid;
            $tenderFee -> updatedby_userid = 0;
            if($request ->hasFile('file')){
                $tenderFee -> file_original_name = $filename_original;
                $tenderFee -> file_new_name = $fileName;
                $tenderFee -> file_type = $mimeType;
                $tenderFee -> file_size = $filesize;
                $tenderFee -> ext = $ext;
            }
            $tenderFee ->save();

          
        }

        if ($tenderFee) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender fee Saved Succssfully!',
                'id' => $tenderFee['id'],
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
     * @param  \App\Models\BidCreationTenderFee  $bidCreationTenderFee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $BidCreationTenderFee = BidCreationTenderFee::where('bidCreationMainId',$id)->get();

        if (count($BidCreationTenderFee) > 0){

            $filename = $BidCreationTenderFee[0]['file_new_name'];
            // $file = public_path()."/uploads/BidManagement/tenderfee/".$filename;

            return response()->json([
                'status' => 200,
                'BidCreationTenderFee' => $BidCreationTenderFee[0],
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
     * @param  \App\Models\BidCreationTenderFee  $bidCreationTenderFee
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreationTenderFee $bidCreationTenderFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreationTenderFee  $bidCreationTenderFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $tenderFee = null;
        $document = BidCreationTenderFee::find($id);
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

            // $tenderFee =  BidCreationTenderFee::find($id);
            // $tenderFee -> bankname = $request->bankname;
            // $tenderFee -> bankbranch = $request->bankbranch;
            // $tenderFee -> mode = $request->mode;
            // $tenderFee -> dateofsubmission = $request->dateofsubmission;
            // $tenderFee -> bgno = $request->bgno;
            // $tenderFee -> ddno = $request->ddno;
            // $tenderFee -> utrno = $request->utrno;
            // $tenderFee -> dateofissue = $request->dateofissue;
            // $tenderFee -> expiaryDate = $request->expiaryDate;
            // $tenderFee -> refno = $request->refno;
            // $tenderFee -> dateofpayment = $request->dateofpayment;
            // $tenderFee -> value = $request->value;
            // $tenderFee -> bidCreationMainId = $request->bidCreationMainId;
            // // $tenderFee -> createdby_userid = $userid;
            // $tenderFee -> updatedby_userid = $userid;
            // if($request ->hasFile('file')){
            //     $tenderFee -> file_original_name = $filename_original;
            //     $tenderFee -> file_new_name = $fileName;
            //     $tenderFee -> file_type = $mimeType;
            //     $tenderFee -> file_size = $filesize;
            //     $tenderFee -> ext = $ext;
            // }
            // $tenderFee ->save();

            $tenderFee = BidCreationTenderFee::findOrFail($id)->update([

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

        if($tenderFee) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender fee Updated Succssfully!',
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
     * @param  \App\Models\BidCreationTenderFee  $bidCreationTenderFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidCreationTenderFee $bidCreationTenderFee)
    {
        //
    }

    public function getdocs($id){

        $doc = BidCreationTenderFee::find($id);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/tenderfee/".$filename;
            return response()->download($file);
        }

    }
}
