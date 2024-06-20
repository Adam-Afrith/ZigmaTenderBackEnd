<?php

namespace App\Http\Controllers;

use App\Models\CountryMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Token;


class CountryMasterController extends Controller
{

    public function index()
    {

        $country = CountryMaster::orderBy('created_at', 'desc')->get();
        //->paginate(4)->appends(['sort'=>'ulb_name']);

        if ($country)
            return response()->json([
                'status' => 200,
                'country' => $country
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
        //
    }


    public function store(Request $request)
    {
        //
        $country = CountryMaster::where('country_name', '=', $request->country_name)->exists();
        if ($country) {
            return response()->json([
                'status' => 400,
                'errors' => 'Country Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['country_name' => 'required|string', 'country_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $country = CountryMaster::firstOrCreate($request->all());
        if ($country) {
            return response()->json([
                'status' => 200,
                'message' => 'Country Has created Succssfully!'
            ]);
        }


    }


    public function show($id)
    {
        //
        $country = CountryMaster::find($id);
        if ($country)
            return response()->json([
                'status' => 200,
                'country' => $country
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function edit(CountryMaster $countryMaster)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
        $country = CountryMaster::where('country_name', '=', $request->country_name)
        ->where('id', '!=', $id)->exists();
        if ($country) {
            return response()->json([
                'status' => 400,
                'errors' => 'Country Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['country_name' => 'required|string', 'country_status' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $country = CountryMaster::findOrFail($id)->update($request->all());
        if ($country)
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


    public function destroy($id)
    {
        //

        try{
            $country = CountryMaster::destroy($id);
            if($country)
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

    public function getList(){

        $countrys = CountryMaster::where("country_status", "=", "Active")->get();

        $countryList= [];
        foreach($countrys as $country){
            $countryList[] = ["value" => $country['id'], "label" =>  $country['country_name']] ;
        }
        return  response()->json([
            'countryList' =>  $countryList,

        ]);
    }


    public function getListofcountry($savedcountry){
        $countrys = CountryMaster::where("country_status", "=", "Active")
        ->orWhere("id",$savedcountry)->get();

        $countryList= [];
        foreach($countrys as $country){
            $countryList[] = ["value" => $country['id'], "label" =>  $country['country_name']] ;
        }
        return  response()->json([
            'countryList' =>  $countryList,

        ]);
    }

    public function CountryMasterTable(Request $request)
    {
        $user = Token::where('tokenid', $request->tokenid)->first();   
        $userid = $user['userid'];
        $accessor =[];
        if( $userid){
            $header=['Country Name','STATUS'];
            $country = DB::table('country_masters')->orderBy('created_at', 'desc')->get();
            foreach($country[0] as $key => $value ){
                if($key === 'country_name' || $key === 'country_status'){
                    $accessor[] = $key;
                }
                
            } 
    
            return response()->json([
                'status'=>200,
                'data'=> $country,
                'header'=> $header,
                'title'=>'Country Master',
                'accessor'=> $accessor,
            ]);
        }
       
    }
}
