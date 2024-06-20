<?php

namespace App\Http\Controllers;

use App\Models\CommunicationDoc;
use Illuminate\Http\Request;
use App\Models\Token;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;

class CommunicationDocController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if($request ->hasFile('file')){
            $file = $request->file('file');
            $filename_original = $file->getClientOriginalName();
            $fileName=$file->hashName();
            // $fileName =intval(microtime(true) * 1000) . $filename_original;
            // $file->storeAs('BidManagement/biddocs', $fileName, 'public');
            $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public'); 
            $mimeType =  $file->getMimeType();
            $filesize = ($file->getSize())/1000;
            $ext =  $file->extension();

            $user = Token::where('tokenid', $request->tokenid)->first();   
            $userid = $user['userid'];

            if($user){
                $CommFiles = new CommunicationDoc;
                $CommFiles -> commId = $request->commId ;
                $CommFiles -> file_original_name = $filename_original ;
                $CommFiles -> file_new_name = $fileName ;
                $CommFiles -> bidCreationMainId = $request -> bid_creation_mainid ;
                $CommFiles -> file_type = $mimeType ;
                $CommFiles -> file_size =  $filesize;
                $CommFiles -> ext =  $ext;
                $CommFiles -> createdby_userid = $userid ;
                $CommFiles -> updatedby_userid = 0 ;
                $CommFiles -> save();
            }
            if ($CommFiles) {
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
   public function show(CommunicationDoc $bidCreation_Creation_Docs)
    {
        //
    }

    public function edit(CommunicationDoc $bidCreation_Creation_Docs)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $CommFiles = null;  
        if($request ->hasFile('file')){
            $document = CommunicationDoc::find($id);
            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/WorkOrder/CommunicationFiles/".$filename;
            
            if(File::exists($file_path)) {
                if(File::delete($file_path)){
                    $file = $request->file('file');
                    $filename_original = $file->getClientOriginalName();
                    $fileName =intval(microtime(true) * 1000) . $filename_original;
                    $file->storeAs('BidManagement/biddocs', $fileName, 'public');
                    $mimeType =  $file->getMimeType();
                    $filesize = ($file->getSize())/1000;
                    $ext =  $file->extension();

                    $user = Token::where('tokenid', $request->tokenid)->first();   
                    $userid = $user['userid'];

                    if($userid){
                        $CommFiles = CommunicationDoc::find($id);
                        $CommFiles -> docname = $request->docname ;
                        $CommFiles -> file_original_name = $filename_original ;
                        $CommFiles -> file_new_name = $fileName ;
                        $CommFiles -> bidCreationMainId = $request -> bid_creation_mainid ;
                        $CommFiles -> file_type = $mimeType ;
                        $CommFiles -> file_size =  $filesize;
                        $CommFiles -> ext =  $ext;
                        $CommFiles -> updatedby_userid =  $userid ;
                        $CommFiles -> save();
                    }
                }
            }
        }

        if ($CommFiles) {
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

   
    public function destroy($id)
    {
        //
        try{
            $document = CommunicationDoc::find($id);

            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/WorkOrder/CommunicationFiles/".$filename;

            if(File::exists($file_path)) {
                File::delete($file_path);
            }

            $doc = CommunicationDoc::destroy($id);
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
        $docs = DB::table('bid_creation__creation__docs')
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

        $doc = CommunicationDoc::find($fileName);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/WorkOrder/CommunicationFiles/".$filename;
            return response()->download($file);
        }

    }

  

}
    