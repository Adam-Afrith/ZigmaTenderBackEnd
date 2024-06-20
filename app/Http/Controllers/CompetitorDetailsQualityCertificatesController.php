<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\CompetitorDetailsQualityCertificates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompetitorDetailsQualityCertificatesController extends Controller
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
        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileExt = $file->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            // $fileName1=$file->hashName();
            $fileName1=$file->getClientOriginalName();
            $filenameSplited=explode(".",$fileName1);
            if($filenameSplited[1]!=$fileExt)
            {
            $fileName=$filenameSplited[0].".".$fileExt;
            }
            else{
                $fileName=$fileName1;   
            }
            $file->storeAs('competitor/qc', $fileName, 'public');  
            $user = Token::where("tokenid", $request->tokenId)->first(); 
            $request->request->add(['cr_userid' => $user['userid']]);
            $request->request->add(['filepath' => $fileName]);
            $request->request->remove('tokenId');
            $request->request->add(['filetype' => $fileExt]);
            // print_r($request['file']);
            // $request->request->remove('file');
            $dataToInsert = $request->except(['file']);
    
            $existence = CompetitorDetailsQualityCertificates::where("compNo", $request->compNo)
                ->where("compId", $request->compId)
                ->where("cerName", $request->cerName)->exists();
                
            if ($existence) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Certificate Name Already Exists!'
                ]);
            }
           
            $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','cerName'=>'required|string', 'remark'=>'nullable|string','cr_userid'=>'required|integer','filepath'=>'required|string']);
            // foreach ($validator as $key => $value)
            // {
            //     if(empty($validator[$key])) {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'message' =>"Not able to Add details now..!",
                    'error'=> $validator->messages(),
                ]);
                }
            // }
            $qcRes = CompetitorDetailsQualityCertificates::firstOrCreate($dataToInsert);
            if ($qcRes) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Added Succssfully!',
                ]);
            }

        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Unable to save!'
            ]);
        }
    }

   
    public function show($id)
    {
         $qc = CompetitorDetailsQualityCertificates::where('id',$id)
        ->select('*')
        ->get();
        
        
        if ($qc)
        {
            return response()->json([
                'status' => 200,
                'qc' => $qc
            ]);
        }
            else {  
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
    
    if($request->hasFile('file')){
        $file = $request->file('file');
        $fileExt = $file->getClientOriginalExtension();
        // $fileName1=$file->hashName();
        $fileName1=$file->getClientOriginalName();
        //received File extentions sometimes converted by browsers
        //Have to set orignal file extention before save
        $filenameSplited=explode(".",$fileName1);
        if($filenameSplited[1]!=$fileExt)
        {
        $fileName=$filenameSplited[0].".".$fileExt;
        }
        else{
            $fileName=$fileName1;   
        }
        $file->storeAs('competitor/qc', $fileName, 'public');  
        // $file->storeAs('uploads/image/competitor/qc', $fileName, 'public');
        
        
        //to delete Existing Image from storage
        $data = CompetitorDetailsQualityCertificates::find($id);
        $image_path = public_path()."/uploads/competitor/qc/".$data->filepath;
        unlink($image_path);
       
        $user = Token::where("tokenid", $request->tokenId)->first();   
        $request->request->add(['edited_userid' => $user['userid']]);
        $request->request->remove('tokenId');
        $request->request->add(['filepath' => $fileName]);
        $request->request->add(['filetype' => $fileExt]);

        $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','cerName'=>'required|string', 'filepath'=>'required|string','remark'=>'nullable|string','edited_userid'=>'required|integer']);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' =>"Not able to update details now..!",
                'error'=> $validator->messages(),
            ]);
            }
    $dataToUpdate = $request->except(['file']);
    $qcedit = CompetitorDetailsQualityCertificates::findOrFail($id)->update($dataToUpdate);
    if ($qcedit)
        return response()->json([
            'status' => 200,
            'message' => "Updated Successfully!"
        ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
}
    else{
        
            $user = Token::where("tokenid", $request->tokenId)->first();  
            
            $request->request->add(['edited_userid' => $user['userid']]);
            // $request->request->add(['filepath' => $fileName]);
            $request->request->remove('tokenId');
        
            $validator = Validator::make($request->all(), ['compId' => 'required|integer','compNo' => 'required|string','cerName'=>'required|string', 'remark'=>'nullable|string','edited_userid'=>'required|integer']);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'message' =>"Not able to update details now..!",
                    'error'=> $validator->messages(),
                ]);
                }
        
        $qcedit = CompetitorDetailsQualityCertificates::findOrFail($id)->update($request->all());
        if ($qcedit)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
        }
        

    }
       


   
    public function destroy($id)
    {
        try{
        
        //to delete Existing Image from storage
        $data = CompetitorDetailsQualityCertificates::find($id);
        // $image_path = public_path('competitorQC').'/'.$data->filepath;
        $image_path = public_path()."/uploads/competitor/qc/".$data->filepath;
        unlink($image_path);

            $qc = CompetitorDetailsQualityCertificates::destroy($id);
            if($qc)    
            {
                return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!",
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!?',
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
    public function getQCList($compid)
    {
        $qc = CompetitorDetailsQualityCertificates::where("compId",$compid)
        ->select('*')
        ->get();
        
        
        if ($qc)
        {
            return response()->json([
                'status' => 200,
                'qc' => $qc
            ]);
        }
            else {  
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id)
    {
        $doc = CompetitorDetailsQualityCertificates::where('id', $id)
            ->select("filepath")
            ->get();


        if (!empty($doc[0]['filepath'])) {
            $header= [
                'filename= $doc[0][filepath]',
            ];
    
            $file = public_path() . "/uploads/competitor/qc/" . $doc[0]['filepath'];
            return response()->download($file, $doc[0]['filepath'], $header);

        } else {
            return response()->json([
                'file' => 'File not found.'
            ], 204);
        }
    }

  
}
