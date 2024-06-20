<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderLetterOfAcceptence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BidManagementWorkOrderLetterOfAcceptenceController extends Controller
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
        if($request->hasFile('wofile')){
            $wofile = $request->file('wofile');
            $wofile_original = $wofile->getClientOriginalName();
            $wofile_fileName =intval(microtime(true) * 1000) . $wofile_original;
            $wofile->storeAs('BidManagement/WorkOrder/LetterOfAcceptence/Document/', $wofile_fileName, 'public');
            $wofile_mimeType =  $wofile->getMimeType();
            $wofile_filesize = ($wofile->getSize())/1000;
            $wofile_ext =  $wofile->extension();
        }
            $data=(array) $request->all();   

        //     $validator = Validator::make($data, [
        //     'Date' => 'required|date',
        //     'from' => 'required|string',
        //     'medRefrenceNo' => 'required|string',
        //     'medium' => 'required|string',
        //     'mediumSelect' => 'required|string',
        //     'refrenceNo' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' =>"Not able to Add Now..!",
        //         'error' => $validator->messages(),
        //     ]);
        // }
       
            // $wofile = $request->file('wofile');
            // $wofile_original = $wofile->getClientOriginalName();
            // $wofile_fileName =intval(microtime(true) * 1000) . $wofile_original;
            // $wofile->storeAs('BidManagement/WorkOrder/LetterOfAcceptence/Document/', $wofile_fileName, 'public');
            // $wofile_mimeType =  $wofile->getMimeType();
            // $wofile_filesize = ($wofile->getSize())/1000;
            // $wofile_ext =  $wofile->extension();

        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $request->request->remove('tokenid');
    if($userid)
    {
        $letteracceptance = new BidManagementWorkOrderLetterOfAcceptence;
        $letteracceptance -> bidid = $request->bidid;
        $letteracceptance -> date = $request->Date;
        $letteracceptance -> refrence_no = $request->refrenceNo;
        // $letteracceptance -> from = $request->from;
        // $letteracceptance -> medium = $request->medium;
        // $letteracceptance -> med_refrence_no = $request->medRefrenceNo;
        // $letteracceptance -> medium_select = $request->mediumSelect;
        
        if(isset($wofile_fileName)){
            $letteracceptance -> wofile = $wofile_fileName;
        }     
        $letteracceptance -> createdby_userid = $userid;
        $letteracceptance -> save();
    }
        if ($letteracceptance) {
            return response()->json([
                'status' => 200,
                'message' => 'Letter Acceptance Has created Succssfully!',
                'letteracceptance' => $letteracceptance,
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
     * @param  \App\Models\BidManagementWorkOrderLetterOfAcceptence  $bidManagementWorkOrderLetterOfAcceptence
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $letterofaccepttance = BidManagementWorkOrderLetterOfAcceptence::where('bidid','=',$id)->get();
        if ($letterofaccepttance){
            return response()->json([
                'status' => 200,
                'letterofaccepttance' => $letterofaccepttance,
            ]);
        }   
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
     * @param  \App\Models\BidManagementWorkOrderLetterOfAcceptence  $bidManagementWorkOrderLetterOfAcceptence
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderLetterOfAcceptence $bidManagementWorkOrderLetterOfAcceptence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderLetterOfAcceptence  $bidManagementWorkOrderLetterOfAcceptence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $wofile_fileName="";
        if($request->hasFile('wofile')){
            $data=(array) $request->all();   

        //     $validator = Validator::make($data, [
        //     'Date' => 'required|date',
        //     'from' => 'required|string',
        //     'medRefrenceNo' => 'required|string',
        //     'medium' => 'required|string',
        //     'mediumSelect' => 'required|string',
        //     'refrenceNo' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' =>"Not able to Update now..!",
        //         'error' => $validator->messages(),
        //     ]);
        // }
            $doc = BidManagementWorkOrderLetterOfAcceptence::where('id','=',$id)->get();
            $wofile_filename = $doc[0]['wofile'];
            $wofile_path = public_path()."/uploads/BidManagement/WorkOrder/LetterOfAcceptence/Document/".$wofile_filename;
            
            if(File::exists($wofile_path)) {
                // if(File::delete($wofile_path)){
                    File::delete($wofile_path);
               // wofile update
               $wofile_filename="";
            }
               $wofile = $request->file('wofile');
               $wofile_original = $wofile->getClientOriginalName();
               $wofile_fileName =intval(microtime(true) * 1000) . $wofile_original;
               $wofile->storeAs('BidManagement/WorkOrder/LetterOfAcceptence/Document/', $wofile_fileName, 'public');
               $wofile_mimeType =  $wofile->getMimeType();
               $wofile_filesize = ($wofile->getSize())/1000;
               $wofile_ext =  $wofile->extension();
               
            }
        // else{

        //     $image_path = public_path('uploads/BidManagement/tenderawarded') . '/' . $getExistingData['document'];
        //             $path = str_replace("\\", "/", $image_path);
        //             if (File::exists($path)) {
        //                 unlink($path);
        //             }
        // }
    // }
               $user = Token::where('tokenid', $request->tokenid)->first();   
               $userid =$user['userid'];
               $request->request->remove('tokenid');
                  
               if($userid){
                $letteracceptance =  BidManagementWorkOrderLetterOfAcceptence::find($id);
                $letteracceptance -> bidid = $request->bidid;
                $letteracceptance -> date = $request->Date;
                $letteracceptance -> refrence_no = $request->refrenceNo;
                // $letteracceptance -> from = $request->from;
                // $letteracceptance -> medium = $request->medium;
                // $letteracceptance -> med_refrence_no = $request->medRefrenceNo;
                // $letteracceptance -> medium_select = $request->mediumSelect;
                $letteracceptance -> wofile = $wofile_fileName;
                $letteracceptance -> updatedby_userid = $userid;
                $letteracceptance -> save();
                 }  
                    if ($letteracceptance) {
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
    //         }
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderLetterOfAcceptence  $bidManagementWorkOrderLetterOfAcceptence
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function wodownload($id){
        
        $doc = BidManagementWorkOrderLetterOfAcceptence::where('bidid','=',$id)->get();
        
        // if($doc[0]['wofile']!='' && $doc[0]['wofile']!=null){
        // echo $doc[0]['wofile'];
            if($doc[0]['wofile']!=null){

               
            $wofile_name = $doc[0]['wofile'];
            $wofile = public_path()."/uploads/BidManagement/WorkOrder/LetterOfAcceptence/Document/".$wofile_name;
            //return $wofile;
            //return response()->JSON(["wofile" => $wofile]);
            return response()->download($wofile);
        }else{
            return response()->json([
                "wofile" => null,
            ],204);
        }
    }
}
