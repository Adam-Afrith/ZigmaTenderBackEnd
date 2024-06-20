<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderCommunicationFiles;
use App\Models\BidManagementWorkOrderCommunicationFilesSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
// use File;
use Illuminate\Support\Facades\File;

class BidManagementWorkOrderCommunicationFilesController extends Controller
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

        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $originalfileName = $file->getClientOriginalName();
        //     $FileExt = $file->getClientOriginalExtension();
        //     $filenameSplited = explode(".", $originalfileName);
        //     $hasfileName = $file->hashName();
        //     $hasfilenameSplited = explode(".", $hasfileName);
        //     $fileName = $hasfilenameSplited[0] . "." . $filenameSplited[1];


        $user = Token::where('tokenid', $request->tokenid)->first();
        // $userid = $user['userid'];
        $request->request->remove('tokenid');
        if ($user['userid']) {

            // random

            $Find = BidManagementWorkOrderCommunicationFiles::where('randomno', '=', $request->random)->get();

            $count = $Find->count();


            if ($count == 0) {
                $CommunicationFiles = new BidManagementWorkOrderCommunicationFiles;
                $CommunicationFiles->bidid = $request->bidid;
                $CommunicationFiles->date = $request->date;
                $CommunicationFiles->refrenceno = $request->refrenceno;
                $CommunicationFiles->from = $request->from;
                $CommunicationFiles->to = $request->to;
                $CommunicationFiles->subject = $request->subject;
                $CommunicationFiles->medium = $request->medium;
                $CommunicationFiles->med_refrenceno = $request->medrefrenceno;
                $CommunicationFiles->randomno = $request->random;

                $CommunicationFiles->createdby_userid = $user['userid'];
                //$CommunicationFiles -> updatedby_userid = 0 ;
                $CommunicationFiles->save();
            } else {
                foreach ($Find as $row) {

                    $update_id = $row->id;
                }
                $GETREFNO = BidManagementWorkOrderCommunicationFiles::where('id', '=', $update_id)
                    ->update(

                        [

                            'bidid' => $request->bidid,
                            'date' => $request->date,
                            'refrenceno' => $request->refrenceno,
                            'from' => $request->from,
                            'to' => $request->to,
                            'subject' => $request->subject,
                            'medium' => $request->medium,
                            'med_refrenceno' => $request->medrefrenceno,
                            'randomno' => $request->random,

                            'createdby_userid' => $user['userid'],
                        ]
                    );


            }


        }
        // $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');
        return response()->json([
            'status' => 200,
            'message' => 'Uploaded Succcessfully',
        ]);

        // } else {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Unable to save!'
        //     ]);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CommunicationFiles = BidManagementWorkOrderCommunicationFiles::where('bidid', '=', $id)->get();
        if ($CommunicationFiles) {
            return response()->json([
                'status' => 200,
                'CommunicationFiles' => $CommunicationFiles,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderCommunicationFiles $bidManagementWorkOrderCommunicationFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenid)->first();
        $request->request->add(['updatedby_userid' => $user['userid']]);
        if ($user['userid']) {

            $request->request->remove('tokenid');
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $originalfileName = $file->getClientOriginalName();
                $FileExt = $file->getClientOriginalExtension();
                $filenameSplited = explode(".", $originalfileName);
                $hasfileName = $file->hashName();
                $hasfilenameSplited = explode(".", $hasfileName);

                $fileName = $hasfilenameSplited[0] . "." . $filenameSplited[1];

                //to delete Existing Image from storfage
                $data = BidManagementWorkOrderCommunicationFiles::where("id", "=", $id)->select("*")->get();

                $image_path = public_path('uploads/BidManagement/WorkOrder/CommunicationFiles') . '/' . $data[0]->comfile;
                // $image_path = public_path('uploads/BidManagement/WorkOrder/CommunicationFiles').'/MwT5orH0qO9KxKSSCSHNVNgdByc2JK3IWUWeAd51.jpg';

                $path = str_replace("\\", "/", $image_path);
                unlink($path);
                $file->storeAs('BidManagement/WorkOrder/CommunicationFiles/', $fileName, 'public');


                $request->request->add(['comfile' => $fileName]);
                $request->request->add(['filetype' => $FileExt]);
            }
            $dataToUpdate = $request->except(['file', '_method']);
            $qcedit = BidManagementWorkOrderCommunicationFiles::where("id", $id)->update($dataToUpdate);

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

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderCommunicationFiles  $bidManagementWorkOrderCommunicationFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            //to delete Existing Image from storage
            $data = BidManagementWorkOrderCommunicationFiles::find($id);

            // $image_path = public_path() . "/uploads/BidManagement/WorkOrder/CommunicationFiles/" . $data->comfile;
            // unlink($image_path);

            $get_random = BidManagementWorkOrderCommunicationFiles::where('id', '=', $id)
                ->get();
            foreach ($get_random as $row) {
                $randomno = $row->randomno;

            }


            $get_image = BidManagementWorkOrderCommunicationFilesSub::where('randomno', '=', $randomno)
                ->get();
            foreach ($get_image as $row) {

                $comfile = $row->comfile;
                $del_id = $row->id;
                $destinationPath = 'uploads/BidManagement/WorkOrder/CommunicationFiles/';
                $destinationPath1 = 'uploads/BidManagement/WorkOrder/CommunicationFiles/' . $comfile;

                if (file_exists($destinationPath)) {
                    File::delete($destinationPath, $comfile);
                    unlink($destinationPath1);

                }

            }


            $comm = BidManagementWorkOrderCommunicationFiles::destroy($id);
            if ($comm) {

                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!",
                ]);

            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect!?',
                    "errormessage" => "",
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    public function getComList($id)
    {

        $CommunicationFiles = BidManagementWorkOrderCommunicationFiles::where('bidid', '=', $id)->get();
        if ($CommunicationFiles) {
            return response()->json([
                'status' => 200,
                'comm' => $CommunicationFiles,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function communicationfileUpload(Request $request)
    {



        $last_id = $request->fbid;

        $file = $request->file('file');
        $path = $request->file->getClientOriginalName();
        $slipt = explode('.', $path);
        // $destinationPath = 'WorkOrderCommunicationFiles';
        $destinationPath = 'uploads/BidManagement/WorkOrder/CommunicationFiles/';
        $new_file_name = 'communicationfile' . time() . '.' . $slipt[1];
        $result = $file->move($destinationPath, $new_file_name);



        $user = Token::where('tokenid', $request->tokenid)->first();
        // $userid = $user['userid'];
        $request->request->remove('tokenid');


        if ($user['userid']) {

            $Find = BidManagementWorkOrderCommunicationFiles::where('randomno', '=', $request->sub_id)->get();
            $count = $Find->count();
            if ($count == 0) {


                $CommunicationFiles = new BidManagementWorkOrderCommunicationFiles;
                $CommunicationFiles->bidid = $request->bidid;
                $CommunicationFiles->date = $request->date;
                $CommunicationFiles->refrenceno = $request->refrenceno;
                $CommunicationFiles->from = $request->from;
                $CommunicationFiles->to = $request->to;
                $CommunicationFiles->randomno = $request->sub_id;
                $CommunicationFiles->subject = $request->subject;
                $CommunicationFiles->medium = $request->medium;
                $CommunicationFiles->med_refrenceno = $request->medrefrenceno;

                $CommunicationFiles->createdby_userid = $user['userid'];
                //$CommunicationFiles -> updatedby_userid = 0 ;
                $CommunicationFiles->save();
                $get_id = BidManagementWorkOrderCommunicationFiles::orderBy('id', 'desc')
                    ->first('id');
                $last_id = $CommunicationFiles->id;



                $CommunicationFilesSub = new BidManagementWorkOrderCommunicationFilesSub;
                $CommunicationFilesSub->randomno = $request->sub_id;
                $CommunicationFilesSub->mainid = $last_id;
                $CommunicationFilesSub->comfile = $new_file_name;
                $CommunicationFilesSub->filetype = $slipt[1];
                $CommunicationFilesSub->createdby_userid = $user['userid'];
                $CommunicationFilesSub->save();



            } else {
                foreach ($Find as $row) {
                    $last_id = $row->id;

                }

                $CommunicationFilesSub = new BidManagementWorkOrderCommunicationFilesSub;
                $CommunicationFilesSub->randomno = $request->sub_id;
                $CommunicationFilesSub->mainid = $last_id;
                $CommunicationFilesSub->comfile = $new_file_name;
                $CommunicationFilesSub->filetype = $slipt[1];
                $CommunicationFilesSub->createdby_userid = $user['userid'];
                $CommunicationFilesSub->save();
            }




            return response()->json([
                'status' => 200,
                'message' => 'Uploaded Succcessfully',


            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }


    }
    public function communicationfileUploadlist(Request $request)
    {
        $imagelist = BidManagementWorkOrderCommunicationFilesSub::where('randomno', '=', $request->sub_id)->get();
        // where('randomno', '=', $request->sub_id)->
        return response()->json([
            'status' => 200,
            'list' => $imagelist
        ]);
    }
    public function communicationfiledelete(Request $request, $id)
    {
        $list_files = BidManagementWorkOrderCommunicationFilesSub::where('id', '=', $id)
            ->get('comfile');
        foreach ($list_files as $row) {
            $image_name = $row->comfile;
        }
        $destinationPath = 'uploads/BidManagement/WorkOrder/CommunicationFiles/';
        $destinationPath1 = 'uploads/BidManagement/WorkOrder/CommunicationFiles/' . $image_name;
        //echo file_exists($destinationPath);
        if (file_exists($destinationPath)) {
            File::delete($destinationPath, $image_name);
            unlink($destinationPath1);
            $list_files = BidManagementWorkOrderCommunicationFilesSub::where('id', '=', $id)
                ->delete();
        } else {
            $list_files = BidManagementWorkOrderCommunicationFilesSub::where('id', '=', $id)
                ->delete();
        }

        return response()->json([
            'status' => 200,

        ]);



    }

}