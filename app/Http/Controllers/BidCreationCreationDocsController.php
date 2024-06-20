<?php

namespace App\Http\Controllers;

use App\Models\BidCreation_Creation_Docs;
use Illuminate\Http\Request;
use App\Models\Token;

use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Support\Facades\File;

class BidCreationCreationDocsController extends Controller
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
            $file->storeAs('BidManagement/biddocs', $fileName, 'public');
            $mimeType =  $file->getMimeType();
            $filesize = ($file->getSize())/1000;
            $ext =  $file->extension();

            $user = Token::where('tokenid', $request->tokenid)->first();   
            $userid = $user['userid'];

            if($user){
                $BidCreation = new BidCreation_Creation_Docs;
                $BidCreation -> docname = $request->docname ;
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
     * @param  \App\Models\BidCreation_Creation_Docs  $bidCreation_Creation_Docs
     * @return \Illuminate\Http\Response
     */
    public function show(BidCreation_Creation_Docs $bidCreation_Creation_Docs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidCreation_Creation_Docs  $bidCreation_Creation_Docs
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreation_Creation_Docs $bidCreation_Creation_Docs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreation_Creation_Docs  $bidCreation_Creation_Docs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $BidCreation = null;
       
        if($request ->hasFile('file')){
            $document = BidCreation_Creation_Docs::find($id);
            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/biddocs/".$filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);
            
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
                        $BidCreation = BidCreation_Creation_Docs::find($id);
                        $BidCreation -> docname = $request->docname ;
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
     * @param  \App\Models\BidCreation_Creation_Docs  $bidCreation_Creation_Docs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $document = BidCreation_Creation_Docs::find($id);

            $filename = $document['file_new_name'];
            $file_path = public_path()."/uploads/BidManagement/biddocs/".$filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if(File::exists($file_path)) {
                File::delete($file_path);
            }

            $doc = BidCreation_Creation_Docs::destroy($id);
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

        $doc = BidCreation_Creation_Docs::find($fileName);

        if($doc){
            $filename = $doc['file_new_name'];
            $file = public_path()."/uploads/BidManagement/biddocs/".$filename;
            // $file =  storage_path('app/public/BidDocs/'.$filename);
            // return response()->json([
            //     'file' =>  $file,
            //     'message' => 'The provided credentials are incorrect.'
            // ]);
            return response()->download($file);
        }

    }

  

}
    