<?php

namespace App\Http\Controllers;

use App\Models\communicationfilesmaster;
use App\Models\communicationfilesmaster_files;
use App\Models\CustomerCreationProfile;
use App\Models\Token;


use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class CommunicationfilesmasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        DB::enableQueryLog(); 
        $communicationFiles = DB::table('communicationfilesmasters')
        ->select('*')
        ->addselect(DB::raw("(CASE 
            WHEN from_ulb THEN (SELECT customer_name FROM customer_creation_profiles where id=from_ulb)
            ELSE `from`
            END) as fromvalue"))
        ->addselect(DB::raw("(CASE 
            WHEN to_ulb THEN (SELECT customer_name FROM customer_creation_profiles where id=to_ulb)
            ELSE `to`
            END) as tovalue"))
        ->orderBy('id', 'DESC')
        ->get();
        $sqlquery = DB::getQueryLog();
        
        $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);
          

        return response()->json([
            'communicationFiles' =>   $communicationFiles,
            'bidcreationList' => [],
            'sql' => $SQL
        ]);
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
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];

       if($user){
        $comFiles = new communicationfilesmaster;
        $comFiles -> date                    = $request->date ;
        $comFiles -> refrence_no             = $request->refrence_no ;
        if($request->fromselect === 'ULB'){
            $comFiles -> from_ulb                = $request ->from ;
           
        }else{
            $comFiles -> from                    = $request->from ;
          
        }
        if($request->toselect === 'ULB'){
            $comFiles -> to_ulb                  = $request->to ;
          
        }else{
            $comFiles -> to                      = $request->to ;
            
        }
        $comFiles -> subject                 = $request->subject ;
        $comFiles -> medium                  = $request->medium ;
        $comFiles -> med_refrence_no         = $request->med_refrence_no ;
        $comFiles -> toselect                = $request->toselect ;
        $comFiles -> fromselect              = $request->fromselect ;   
        $comFiles -> createdby_userid        = $userid ;
        $comFiles -> updatedby_userid        = 0 ;
        $comFiles -> save();
        } 

        if($comFiles){

            for($i=1; $i<=($request->filecount); $i++){
                if($request ->hasFile('file'.$i)){
                    $file = $request->file('file'.$i);
                    $filename_original = $file->getClientOriginalName();
                    $fileName =intval(microtime(true) * 1000) . $filename_original;
                    $file->storeAs('Communicationfiles', $fileName, 'public');
                    $mimeType =  $file->getMimeType();
                    $filesize = ($file->getSize())/1000;
                    $ext =  $file->extension(); 
                    
                    $comFiles_files = new communicationfilesmaster_files;
                    $comFiles_files -> mainid = $comFiles['id'] ;
                    $comFiles_files -> file_original_name = $filename_original ;
                    $comFiles_files -> file_new_name = $fileName ;
                    $comFiles_files -> file_type = $mimeType ;
                    $comFiles_files -> file_size =  $filesize;
                    $comFiles_files -> ext =  $ext;
                    $comFiles_files -> save();
                }
            }



            return response()->json([
                'status'    => 200,
                'message'   => 'Saved Succcessfully',
                'id'        => $comFiles['id']
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
     * @param  \App\Models\communicationfilesmaster  $communicationfilesmaster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
       $communicationfilesmaster = communicationfilesmaster::find($id);
      
        if ($communicationfilesmaster){

            if($communicationfilesmaster['fromselect'] === 'ULB'){
                $ulbName = CustomerCreationProfile::find($communicationfilesmaster['from_ulb']);
                if($ulbName)
                $ulbValue = ["value" => $ulbName['id'], "label" =>  $ulbName['customer_name']];
                else
                $ulbValue = null;
                $communicationfilesmaster['from'] = $ulbValue;
            }

            if($communicationfilesmaster['toselect'] === 'ULB'){
                $ulbName = CustomerCreationProfile::find($communicationfilesmaster['to_ulb']);
                if($ulbName)
                $ulbValue = ["value" => $ulbName['id'], "label" =>  $ulbName['customer_name']];
                else
                $ulbValue = null;
                $communicationfilesmaster['to'] =  $ulbValue;
            }
    
    
            return response()->json([
                'status' => 200,
                'communicationfilesmaster' => $communicationfilesmaster
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
     * @param  \App\Models\communicationfilesmaster  $communicationfilesmaster
     * @return \Illuminate\Http\Response
     */
    public function edit(communicationfilesmaster $communicationfilesmaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\communicationfilesmaster  $communicationfilesmaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
       
            $updatedata['updatedby_userid'] = $userid;
            $updatedata['date']             = $request->date;
            $updatedata['refrence_no']      = $request->refrence_no;

            if($request->fromselect === 'ULB'){
                $updatedata['from_ulb'] = $request ->from ;
                $updatedata['from']     = null ;
            }else{
                $updatedata['from']     = $request ->from;
                $updatedata['from_ulb'] = null ;
            }
            if($request->toselect === 'ULB'){
                $updatedata['to_ulb']   = $request->to ;
                $updatedata['to']       = null ;
            }else{
                $updatedata['to']         = $request->to;
                $updatedata['to_ulb']     = null ;
            }


            $updatedata['subject']          = $request->subject;
            $updatedata['medium']           = $request->medium;
            $updatedata['med_refrence_no']  = $request->med_refrence_no;
            $updatedata['toselect']         = $request->toselect;
            $updatedata['fromselect']       = $request->fromselect;

           
          

            $comFiles = communicationfilesmaster::findOrFail($id)->update($updatedata);

            if($comFiles){

                for($i=1; $i<=($request->filecount); $i++){
                    if($request ->hasFile('file'.$i)){
                        $file = $request->file('file'.$i);
                        $filename_original = $file->getClientOriginalName();
                        $fileName =intval(microtime(true) * 1000) . $filename_original;
                        $file->storeAs('Communicationfiles', $fileName, 'public');
                        $mimeType =  $file->getMimeType();
                        $filesize = ($file->getSize())/1000;
                        $ext =  $file->extension(); 
                        
                        $comFiles_files = new communicationfilesmaster_files;
                        $comFiles_files -> mainid = $id ;
                        $comFiles_files -> file_original_name = $filename_original ;
                        $comFiles_files -> file_new_name = $fileName ;
                        $comFiles_files -> file_type = $mimeType ;
                        $comFiles_files -> file_size =  $filesize;
                        $comFiles_files -> ext =  $ext;
                        $comFiles_files -> save();
                    }
                }
    
    
    
                return response()->json([
                    'status'    => 200,
                    'message'   => 'Updated Succcessfully',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\communicationfilesmaster  $communicationfilesmaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    try{
        $communicationFiles = communicationfilesmaster_files::where('mainid',$id)
        ->select('*')
        ->orderBy('id', 'DESC')
        ->get();

        if($communicationFiles){
            
            foreach($communicationFiles as $communicationFile){
                $filename = $communicationFile['file_new_name'];
                $file_path = public_path()."/uploads/Communicationfiles/".$filename;
                // $file_path =  storage_path('app/public/BidDocs/'.$filename);
    
                if(File::exists($file_path)) {
                   if(File::delete($file_path)){
                    $deletedoc = communicationfilesmaster_files::destroy($communicationFile['id']);
                   }
                }
            }

            $deleterecord = communicationfilesmaster::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully.',
              
            ]);

        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete!',
            ]);
        }

    }catch(\Illuminate\Database\QueryException $ex){
        $error = $ex->getMessage(); 
        
        return response()->json([
            'status' => 404,
            'message' => 'Unable to delete! This data is used in another file/form/table.',
            "errormessage" => $error,
        ]);
    }
    }

    public function docList(Request $request){
        $docs = DB::table('communicationfilesmaster_files')
        ->where('mainid', $request->mainid)
        ->select('*') 
        ->orderBy('id', 'desc')       
        ->get();
    
        if ($docs){

            foreach($docs as  $key => $doc){
                $document = communicationfilesmaster_files::find($doc->id);
    
                $filename = $document['file_new_name'];
                $file_path = public_path()."/uploads/Communicationfiles/".$filename;
                // $file_path =  storage_path('app/public/BidDocs/'.$filename);
    
                if(!File::exists($file_path)) {
                    unset($docs[$key]);
                }
            }
    
    
            return response()->json([
                'status' => 200,
                'docs' => $docs
            ]);
        }

        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function deletefile($id){
        $document = communicationfilesmaster_files::find($id);

            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/Communicationfiles/".$filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if(File::exists($file_path)) {
                File::delete($file_path);
            }


            $doc = communicationfilesmaster_files::destroy($id);
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
    }

    public function download($fileName){

        $doc = communicationfilesmaster_files::find($fileName);

        if($doc){
            $filename = $doc['file_new_name'];
          
         
            $file = public_path()."/uploads/Communicationfiles/".$filename;
            return response()->download($file);
          
           
        }

    }

    public function CommunicationFilesTable(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        if($user['userid'])
        {
            $header = ['Date','From','To','Medium','Med. reference no','Files'];
            $accessor = ['date','from','tovalue','medium','med_refrence_no','files'];

        DB::enableQueryLog(); 
        $communicationFiles = DB::table('communicationfilesmasters')
        ->select('*')
        ->addselect(DB::raw("(CASE 
            WHEN from_ulb THEN (SELECT customer_name FROM customer_creation_profiles where id=from_ulb)
            ELSE `from`
            END) as fromvalue"))
        ->addselect(DB::raw("(CASE 
            WHEN to_ulb THEN (SELECT customer_name FROM customer_creation_profiles where id=to_ulb)
            ELSE `to`
            END) as tovalue"))
        ->orderBy('id', 'DESC')
        ->get();
        $sqlquery = DB::getQueryLog();
        
        $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);
          

        return response()->json([
            'title' => 'CommunicationFiles',
            'header' => $header,
            'accessor' => $accessor,
            'data' =>   $communicationFiles,
            'bidcreationList' => [],
            // 'sql' => $SQL
        ]);
    }
    }
}
