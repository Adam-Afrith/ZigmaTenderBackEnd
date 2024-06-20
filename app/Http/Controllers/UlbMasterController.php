<?php

namespace App\Http\Controllers;

use App\Models\UlbMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UlbMasterController extends Controller
{

    public function index()
    {
        $ulb = UlbMaster::orderBy('created_at', 'desc')->get();
        //->paginate(4)->appends(['sort'=>'ulb_name']);

        if ($ulb)
            return response()->json([
                'status' => 200,
                'ulb' => $ulb
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function create()
    {
        return "Create Function";
    }


    public function store(Request $request)
    {
        $ulbRes = UlbMaster::where('ulb_name', '=', $request->ulb_name)->exists();
        if ($ulbRes) {
            return response()->json([
                'status' => 400,
                'errors' => 'ULB Name Already Exists'
            ]);
        }


        $validator = Validator::make($request->all(), ['ulb_name' => 'required|string', 'ulb_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $ulbObj = UlbMaster::firstOrCreate($request->all());
        if ($ulbObj) {
            return response()->json([
                'status' => 200,
                'message' => 'ULB Has created Succssfully!'
            ]);
        }
    }


    public function show($id)
    {
        $ulb = UlbMaster::find($id);
        if ($ulb)
            return response()->json([
                'status' => 200,
                'ulb' => $ulb
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }


    public function edit($id)
    {
        return "Edit Function";
    }


    public function update(Request $request, $id)
    {

        $ulbRes = UlbMaster::where('ulb_name', '=', $request->ulb_name)
            ->where('id', '!=', $id)->exists();
        if ($ulbRes) {
            return response()->json([
                'status' => 400,
                'message' => 'ULB Name Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['ulb_name' => 'required|string|unique', 'ulb_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->message(),
            ]);
        } 
        
        $ulb = UlbMaster::findOrFail($id)->update($request->all());

            if ($ulb)
                return response()->json([
                    'status' => 200,
                    'message' => "Updated Successfully!",
                ]);
            else{
                return response()->json([
                    'status' => 400,
                    'message' => "Sorry, Not able Update ULB Details now, try again later"
                ]);
            }
        }



    public function destroy($id)
    {
        $ulb = UlbMaster::destroy($id);
        if ($ulb)
            return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);

        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
