<?php

namespace App\Http\Controllers;

use App\Models\CallLogFiles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Storage;


class CallLogFilesController extends Controller
{
    public function store(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenId)->first();
        // echo "User Id - $user[userid]";
        // echo "Has File -".$request->hasFile('file')." --";

        if ($user['userid'] && $request->hasFile('file')) {

            $call_file = $request->file('file');
            $call_file_original = $call_file->getClientOriginalName();
            $call_file_fileName = intval(microtime(true) * 1000) . $call_file_original;
            $call_file->storeAs('CallCreation/CallLog/', $call_file_fileName, 'public');
            $call_file_mimeType =  $call_file->getMimeType();
            $call_file_filesize = ($call_file->getSize()) / 1000;
            // $call_file_ext =  $call_file->extension();

            // $data=(array) $request->all();   

            $request->request->add(['createdby_userid' => $user['userid']]);
            $request->request->add(['hasfilename' => $call_file_fileName]);
            $request->request->add(['originalfilename' => $call_file_original]);
            $request->request->add(['filetype' => $call_file_mimeType]);
            $request->request->add(['filesize' => $call_file_filesize]);

            // return $request;
            
            $call_log_add = CallLogFiles::firstOrCreate($request->except(['file','tokenId']));
            // $call_log_add->save();
            if ($call_log_add) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Call Log Form Created Succssfully!',
                    'subid' => $call_log_add->id,
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Provided Credentials are Incorrect!'
                ]);
            }
        }
    }


    public function destroy($id)
    {
        try{
            $document = CallLogFiles::find($id);
            $filename = $document['hasfilename'];
            $file_path = public_path()."/uploads/CallCreation/CallLog/".$filename;
            // $file_path =  storage_path('app/public/BidDocs/'.$filename);

            if(File::exists($file_path)) {
                File::delete($file_path);
            }
            
            $doc = CallLogFiles::destroy($id);
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

    public function getUplodedDocList($id){
        
        $docs= CallLogFiles::where('mainid', $id)
        ->select('*') 
        ->orderBy('id', 'desc')       
        ->get();
        
        if ($docs)
            return response()->json([
                'status' => 200,
                'docs' => $docs,
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id){
        $doc = CallLogFiles::where('id',$id)->get();
        
        if($doc){
            $file = public_path()."/uploads/CallCreation/CallLog/".$doc[0]->hasfilename;
            return response()->download($file);
        }
    }


    public function getCallCounts(Request $request, $id){
        return "getCallCounts - Request ".$request;
    }
    

}
