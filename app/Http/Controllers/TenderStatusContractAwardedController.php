<?php

namespace App\Http\Controllers;

use App\Models\TenderStatusContractAwarded;
use App\Models\BidManagementTenderOrBidStaus;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\File;

class TenderStatusContractAwardedController extends Controller
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


    public function store(Request $request)
    {
        try {
            $user = Token::where("tokenid", $request->tokenid)->first();
            if ($user['userid']) {
                $isBididExist = TenderStatusContractAwarded::where("bidid", $request->bid_creation_mainid)->first();
                if (!$isBididExist) {
                    // return (empty($request->file));
                    if ($request->hasFile('file')  && !empty($request->file)) {
                        $file = $request->file('file');
                        $filename_original = $file->getClientOriginalName();
                        $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                        $ext =  $file->getClientOriginalExtension();
                        $filenameSplited = explode(".", $fileName1);
                        if ($filenameSplited[1] != $ext) {
                            $fileName = $filenameSplited[0] . "." . $ext;
                        } else {
                            $fileName = $fileName1;
                        }
                        $file->storeAs('BidManagement/tenderawarded', $fileName, 'public');
                    } else {
                        $fileName = '';
                    }
                    $awarded = new TenderStatusContractAwarded;
                    $awarded->bidid = $request->bid_creation_mainid;
                    $awarded->competitorId  = $request->competitorId;
                    $awarded->contactAwardedDate = $request->date;
                    $awarded->document = $fileName;
                    $awarded->description = $request->description;
                    $awarded->created_userid = $user['userid'];
                    $awarded->save();

                    if ($awarded) {
                        if ((BidManagementTenderOrBidStaus::where("bidid", $request->bid_creation_mainid)->exists())) {
                            $updateStatus = BidManagementTenderOrBidStaus::where("bidid", $request->bid_creation_mainid)->update(['status'=> 'Completed']);
                        }
                        // else{
                        //     $insertStatus= new BidManagementTenderOrBidStaus;
                        //     $insertStatus->bidid = $request->bid_creation_mainid;
                        //     $insertStatus->created_by = $user['userid'];
                        //     $insertStatus->status="Completed";
                        //     $insertStatus->save();
                        // }
                        return response()->json([
                            'status' => 200,
                            'message' => 'Award OF Contract Added..!'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Oops, Unable to Add..!',
                            'err' => 'Not able to insert into sub table'
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!',
                'error' => $error
            ]);
        }
    }

    // public function getAwardContractList($id)
    // {
    //     try {

    //         $fetchresult = TenderStatusContractAwarded::where('bidid', $id)
    //             ->get()->first();

    //         if ($fetchresult) {
    //             return response()->json([
    //                 'status' => 200,
    //                 'result' => $fetchresult,
    //                 'date' => $fetchresult->contactAwardedDate,
    //                 'competitorId' => $fetchresult->competitorId,
    //                 'description' => $fetchresult->description,
    //                 'mainId' => $fetchresult->id,
    //                 'filename' => $fetchresult->document,
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'The provided credentials are incorrect.'
    //             ]);
    //         }
    //     } catch (\Exception $ex) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'The provided credentials are incorrect.'
    //         ]);
    //     }
    // }






    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function show($bidid)
    {

        try {

            $fetchresult = TenderStatusContractAwarded::where('bidid', $bidid)
                ->get()->first();

            if ($fetchresult) {
                return response()->json([
                    'status' => 200,
                    'result' => $fetchresult,
                    'date' => $fetchresult->contactAwardedDate,
                    'competitorId' => $fetchresult->competitorId,
                    'description' => $fetchresult->description,
                    'mainId' => $fetchresult->id,
                    'filename' => $fetchresult->document,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mainId)
    {
        //


        // try {
        $user = Token::where("tokenid", $request->tokenid)->first();
        // Have to get Existing Data from table to update using update Method
        $getExistingData = TenderStatusContractAwarded::where("id", $mainId)->get()->first();

        if ($user['userid']) {
            $fileName = "";

            if ($request->hasFile('file')  && !empty($request->file)) {
                $file = $request->file('file');
                $filename_original = $file->getClientOriginalName();
                $fileName1 = intval(microtime(true) * 1000) . $filename_original;
                $ext =  $file->getClientOriginalExtension();
                $filenameSplited = explode(".", $fileName1);
                if ($filenameSplited[1] != $ext) {
                    $fileName = $filenameSplited[0] . "." . $ext;
                } else {
                    $fileName = $fileName1;
                }

                if ($getExistingData['document']) {
                    //to delete existin image           

                    $image_path = public_path('uploads/BidManagement/tenderawarded') . '/' . $getExistingData['document'];
                    $path = str_replace("\\", "/", $image_path);
                    if (File::exists($path)) {
                        unlink($path);
                    }
                }
                $file->storeAs('BidManagement/tenderawarded', $fileName, 'public');

                // return ("Res ".$request->hasFile('file')  && !empty($request->file));
            }

            $awarded = TenderStatusContractAwarded::where("id", $mainId)
                ->update(

                    [
                        'bidid' => $request->input('bid_creation_mainid'),
                        'competitorId' => $request->input('competitorId'),
                        'contactAwardedDate' => $request->input('date'),
                        'document' => $fileName,
                        'description' => $request->input('description'),
                        'edited_userid' => $user['userid'],
                    ]
                );


            if ($awarded) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Awarded Contract Status Updated..!'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Oops, Unable to Add..!',
                    'err' => 'not able to insert into main table'
                ]);
            }
        }

        //     }
        //  catch (\Exception $e) {
        //     $error = $e->getMessage();
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'The provided credentials are incorrect!',
        //         'error' => $error
        //     ]);
        // }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderStatusContractAwarded  $tenderStatusContractAwarded
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderStatusContractAwarded $tenderStatusContractAwarded)
    {
        //
    }
    public function download($bidid)
    {

        $doc = TenderStatusContractAwarded::where('bidid', $bidid)
            ->select("document")
            ->get();

        if (!empty($doc[0]->document)) {
            $file = public_path() . "/uploads/BidManagement/tenderawarded/" . $doc[0]->document;
            return response()->download($file, $doc[0]['document']);
        } else {
            return response()->json([
                'message' => 'File not Available in DB..!',
            ], 204);
        }
    }
}
