<?php

namespace App\Http\Controllers;

use App\Models\CommunicationFiles;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Models\Token;
// use Illuminate\Database\Eloquent\Factories\HasFactory;


class CommunicationFilesController extends Controller
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
        if($request ->hasFile('file')){
            $file = $request->file('file');
            $fileExt = $file->getClientOriginalName();
            $fileName1=$file->hashName();
            $filenameSplited=explode(".",$fileName1);
            if($filenameSplited[1]!=$fileExt)
            {
            $fileName=$filenameSplited[0].".".$fileExt;
            }
            else{
                $fileName=$fileName1;   
            }
            $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');
            $user = Token::where('tokenid', $request->tokenid)->first();   
            $request->request->add(['cr_userid' => $user['userid']]);
            $request->request->add(['filepath' => $fileName]);
            $request->request->remove('tokenid');
            $request->request->add(['filetype' => $fileExt]);
            $request->except(['file']);
            $CommunicationFiles = CommunicationFiles::firstOrCreate($request->all());
            return response() -> json([
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function show(CommunicationFiles $communicationFiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(CommunicationFiles $communicationFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileExt = $file->getClientOriginalExtension();
            $fileName1=$file->hashName();
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
            $file->storeAs('uploads/BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');
            
            
            //to delete Existing Image from storage
            $data = CommunicationFiles::find($id);
            $image_path = public_path('BidManagement/WorkOrder/CommunicationFiles').'/'.$data->filepath;
            unlink($image_path);
           
            $user = Token::where("tokenid", $request->tokenId)->first();   
            $request->request->add(['edited_userid' => $user['userid']]);
            $request->request->remove('tokenId');
            $request->request->add(['filepath' => $fileName]);
            $request->request->add(['filetype' => $fileExt]);
           
            $dataToUpdate = $request->except(['file']);
            $qcedit = CommunicationFiles::findOrFail($id)->update($dataToUpdate);
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
            
                
            
            $qcedit = CommunicationFiles::findOrFail($id)->update($request->all());
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommunicationFiles $communicationFiles)
    {
        //
    }
}
