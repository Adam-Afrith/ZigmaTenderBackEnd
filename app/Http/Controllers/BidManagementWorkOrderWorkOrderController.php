<?php

namespace App\Http\Controllers;

use App\Models\BidManagementWorkOrderWorkOrder;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BidManagementWorkOrderWorkOrderController extends Controller
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

        if ($request->hasFile('wofile')) {
            $data = (array) $request->all();
            //     $validator = Validator::make($data, [
            //     'orderQuantity' => 'required|integer',
            //     'PricePerUnit' => 'required|integer',
            //     'LoaDate' => 'required|date',
            //     'OrderDate' => 'required|date',
            //     'SiteHandOverDate' => 'required|date',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => 400,
            //         'message' =>"Not able to Add Strength/Weakness details now..!",
            //         'error' => $validator->messages(),
            //     ]);
            // }
            //image one upload 
            $wofile = $request->file('wofile');
            $wofile_original = $wofile->getClientOriginalName();
            $wofile_fileName =  $wofile_original;
            $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $wofile_fileName, 'public');
        }
      
        if ($request->hasFile('agfile')) {
            
            //image two upload
            $agfile = $request->file('agfile');
            $agfile_original = $agfile->getClientOriginalName();
            $agfile_fileName = $agfile_original;
            $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $agfile_fileName, 'public');
        }

        if ($request->hasFile('shofile')) {
            //image three upload
            $shofile = $request->file('shofile');
            $shofile_original = $shofile->getClientOriginalName();
            $shofile_fileName = $shofile_original;
            $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $shofile_fileName, 'public');

        }

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        $request->request->remove('tokenid');
        if ($userid) {

            $WorkOrder = new BidManagementWorkOrderWorkOrder;
            $WorkOrder->bidid = $request->bidid;
            $WorkOrder->orderquantity = $request->orderQuantity;
            $WorkOrder->priceperUnit = $request->PricePerUnit;
            $WorkOrder->loadate = $request->LoaDate;
            $WorkOrder->orderdate = $request->OrderDate;
            $WorkOrder->agreedate = $request->AgreeDate;
            $WorkOrder->sitehandoverdate = $request->SiteHandOverDate;
            if (isset($wofile_fileName)) {
                $WorkOrder->wofile = $wofile_fileName;
            }
            if (isset($agfile_fileName)) {
                $WorkOrder->agfile = $agfile_fileName;
            }
            if (isset($shofile_fileName)) {
                $WorkOrder->shofile = $shofile_fileName;
            }
            $WorkOrder->createdby_userid = $userid;
            $WorkOrder->save();

        
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $WorkOrder = BidManagementWorkOrderWorkOrder::where('bidid', '=', $id)->get();
        if ($WorkOrder) {
            return response()->json([
                'status' => 200,
                'WorkOrder' => $WorkOrder,
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
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderWorkOrder $bidManagementWorkOrderWorkOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $doc = BidManagementWorkOrderWorkOrder::find($id); //to handle existing images 
        if ($request->hasFile('wofile')) {
            //     $data=(array) $request->all();   
            //     $validator = Validator::make($data, [
            //         'orderQuantity' => 'required|integer',
            //         'PricePerUnit' => 'required|integer',
            //         'LoaDate' => 'required|date',
            //         'OrderDate' => 'required|date',
            //         'SiteHandOverDate' => 'required|date',
            // ]);


            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => 400,
            //         'message' =>"Not able to Add Strength/Weakness details now..!",
            //         'error' => $validator->messages(),
            //     ]);
            // }

            $wofile_filename = $doc['wofile'];
            $wofile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/workorderDocument/" . $wofile_filename;
            // $woFileName1=$request->wofile->hashName();
            $wofile_fileName = $request->wofile->getClientOriginalName();
            // $woFilenameSplited=explode(".",$woFileName1);
            $woFileExt = $request->wofile->getClientOriginalExtension();

            if (File::exists($wofile_path)) {
                if (File::delete($wofile_path)) {
                    // wofile update
                    $wofile = $request->file('wofile');

                    $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $wofile_fileName, 'public');
                }
            }
        } else {
            $wofile_filename = $doc['wofile'];
            $wofile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/workorderDocument/" . $wofile_filename;
            if (File::delete($wofile_path)) {
                if (File::exists($wofile_path)) {
                    if (File::delete($wofile_path)) {
                        // wofile update
                        $wofile_fileName = "";
                    }
                }
            }
        }

        if ($request->hasFile('agfile')) {
            $agfile_filename = $doc['agfile'];
            $agfile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/agreementDocument/" . $agfile_filename;

            $agFileExt = $request->agfile->getClientOriginalExtension();
            $agfile_fileName = $request->agfile->getClientOriginalName();


            if (File::exists($agfile_path)) {
                if (File::delete($agfile_path)) {
                    // agfile file update
                    $agfile = $request->file('agfile');

                    $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $agfile_fileName, 'public');
                }
            }
        } else {
            $agfile_filename = $doc['agfile'];
            $agfile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/agreementDocument/" . $agfile_filename;
            if (File::exists($agfile_path)) {
                if (File::delete($agfile_path)) {
                    // agfile file update
                    $agfile_fileName = "";
                }
            }
        }

        if ($request->hasFile('shofile')) {
            $shofile_filename = $doc['shofile'];
            $shofile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/" . $shofile_filename;

            $shofile_fileName = $request->shofile->getClientOriginalName();
            $shoFileExt = $request->shofile->getClientOriginalExtension();
          

            if (File::exists($shofile_path)) {
                if (File::delete($shofile_path)) {
                    //shofile  update
                    $shofile = $request->file('shofile');
                    $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $shofile_fileName, 'public');
                }
            }

        } else {
            $shofile_filename = $doc['shofile'];
            $shofile_path = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/" . $shofile_filename;
            if (File::exists($shofile_path)) {
                if (File::delete($shofile_path)) {
                    //shofile  update
                    $shofile_fileName = "";
                }
            }
        }

        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];
        $request->request->remove('tokenid');
        if ($userid) {
            $WorkOrder =  BidManagementWorkOrderWorkOrder::find($id);
            $WorkOrder->bidid = $request->bidid;
            $WorkOrder->orderquantity = $request->orderQuantity;
            $WorkOrder->priceperUnit = $request->PricePerUnit;
            $WorkOrder->loadate = $request->LoaDate;
            $WorkOrder->orderdate = $request->OrderDate;
            $WorkOrder->agreedate = $request->AgreeDate;
            $WorkOrder->sitehandoverdate = $request->SiteHandOverDate;
            if (isset($wofile_fileName)) {
                $WorkOrder->wofile = $wofile_fileName;
            }
            if (isset($agfile_fileName)) {
                $WorkOrder->agfile = $agfile_fileName;
            }
            if (isset($shofile_fileName)) {
                $WorkOrder->shofile = $shofile_fileName;
            }
            $WorkOrder->updatedby_userid = $userid;
            $WorkOrder->save();
        }
        if ($WorkOrder) {
            return response()->json([
                'status' => 200,
                'message' => 'Updated Succcessfully'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to update!'
            ]);
        }
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidManagementWorkOrderWorkOrder $bidManagementWorkOrderWorkOrder)
    {
        //
    }

    public function wodownload($id)
    {
        $doc = BidManagementWorkOrderWorkOrder::where('bidid', '=', $id)->get();
        if ($doc[0]['wofile'] !== null) {
            $wofile_name = $doc[0]['wofile'];
            $wofile = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/workorderDocument/" . $wofile_name;
            return response()->download($wofile);
        }
    }

    public function agdownload($id)
    {
        $doc = BidManagementWorkOrderWorkOrder::where('bidid', '=', $id)->get();
        if ($doc[0]['agfile'] !== null) {
            $agfile_name = $doc[0]['agfile'];
            $agfile = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/agreementDocument/" . $agfile_name;
            return response()->download($agfile);
        }
    }

    public function shodownload($id)
    {
        $doc = BidManagementWorkOrderWorkOrder::where('bidid', '=', $id)->get();
        if ($doc[0]['shofile'] !== null) {
            $shofile_name = $doc[0]['shofile'];
            $shofile = public_path() . "/uploads/BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/" . $shofile_name;
            return response()->download($shofile);
        }
    }

    public function getimagename($id)
    {
        $doc = BidManagementWorkOrderWorkOrder::where('bidid', '=', $id)->get();
        if ($doc) {
            return response()->json([
                'status' => 200,
                'doc' => $doc,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
