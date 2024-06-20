<?php

namespace App\Http\Controllers;
use App\Models\CompetitorProfileCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
class CompetitorProfileCreationController extends Controller
{
    
    public function index()
    {
        $competitor = CompetitorProfileCreation::where('id','!=','')
                    ->select('id','compNo','compName','mobile','email') 
                    ->orderBy('id')
                    ->get();

        if ($competitor)
            return response()->json([
                'status' => 200,
                'competitor' => $competitor
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
        return "In Create Function";
    }



    public function store(Request $request)
    {
        // echo "token ID:" . $curr_token=$request->tokenId;
        $user = Token::where("tokenid", $request->tokenId)->first();
        //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
        $request->request->add(['cr_userid' => $user['userid']]);
        //Here is no need of token id when insert $request into table, so remove it form $request
        $request->request->remove('tokenId');

        $existence = CompetitorProfileCreation::where("compName", $request->compName)->exists();
        if ($existence) {
            return response()->json([
                'status' => 400,
                'message' => 'Competitor Name Already Exists!'
            ]);
        }

    //     $validator = Validator::make($request->all(), ['compName' => 'required|string','compNo'=>'required|string', 'state'=>'required|integer', 'registrationType'=>'required|string', 'registerationYear'=>'required|integer','country'=>'required|integer','district'=>'required|integer','city'=>'required|integer','address'=>'required|string','pincode'=>'required|integer','panNo'=>'required|string','mobile'=>'required|integer','email'=>'required|string','gstNo'=>'required|string','directors'=>'required|string','companyType'=>'required|string','manpower'=>'required|integer','cr_userid'=>'required|integer'
    // ]);
        $validator = Validator::make($request->all(), ['compName' => 'required|string','compNo'=>'required|string', 'state'=>'required|integer', 'country'=>'required|integer','district'=>'required|integer','city'=>'required|integer','cr_userid'=>'required|integer'
    ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => $validator->messages(),
            ]);
        }

        $competitorObj = CompetitorProfileCreation::firstOrCreate($request->all());
        if ($competitorObj) {
            return response()->json([
                'status' => 200,
                'message' => 'Competitor Created Succssfully!',
                'inserted_id' => $competitorObj->id,
            ]);
        }
    }

   
    public function show($id)
    {
         //
         $competitor = CompetitorProfileCreation::find($id);
         if ($competitor)
             return response()->json([
                 'status' => 200,
                 'competitor' => $competitor
             ]);
         else {
             return response()->json([
                 'status' => 404,
                 'message' => 'The provided credentials are incorrect.'
             ]);
         }
    }

    
    public function edit(CompetitorProfileCreation $competitorProfileCreation)
    {
        return "In Edit Function";
    }

    
    public function update(Request $request, $id)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
    //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
    $request->request->add(['edited_userid' => $user['userid']]);
    //Here is no need of token id when insert $request into table, so remove it form $request
    $request->request->remove('tokenId');
    // $validator = Validator::make($request->all(), ['compName' => 'required|string','compNo'=>'required|string', 'state'=>'required|integer','registrationType'=>'required|integer', 'registerationYear'=>'required|integer','country'=>'required|integer','district'=>'required|integer','city'=>'required|integer','address'=>'required|string','pincode'=>'required|integer','panNo'=>'required|string','mobile'=>'required|integer','email'=>'required|string','gstNo'=>'required|string','directors'=>'required|string','companyType'=>'required|string','manpower'=>'required|integer',
    // ]);

    $validator = Validator::make($request->all(), ['compName' => 'required|string','compNo'=>'required|string', 'state'=>'required|integer', 'country'=>'required|integer','district'=>'required|integer','city'=>'required|integer','edited_userid'=>'required|integer'
    ]);
if ($validator->fails()) {
        return response()->json([
            'status' => 404,
            'message' => "Not able to update now",
        ]);
    } 

    $competitor = CompetitorProfileCreation::findOrFail($id)->update($request->all());
    if ($competitor){
        return response()->json([
            'status' => 200,
            'message' => "Updated Successfully!",
        ]);
    }
    else {
        return response()->json([
            'status' => 404,
            'message' => "Sorry, Failed to Update, Try again later"
        ]);
    }
}

   
    public function destroy($id)
    {
        try{
            $comp = CompetitorProfileCreation::destroy($id);
            if ($comp) {
                return response()->json([
                    'status' => 200,
                    'message' => "Deleted Successfully!"
                ]);
            }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The Provided Credentials are Incorrect.',
                "errormessage" => '',
            ]);
        }
    }
    catch(\Illuminate\Database\QueryException $ex){
        $error = $ex->getMessage(); 
            
        return response()->json([
            'status' => 404,
            'message' => 'Unable to delete! This data is used in another file/form/table.',
            "errormessage" => $error,
        ]);
    }
    }
    public function getCompNo($compid)
    {
        $compNo = CompetitorProfileCreation::find($compid);
        
    if ($compNo)
        return response()->json([
            'status' => 200,
            'compNo' => $compNo->compNo
        ]);
        else {
            return response()->json([
                'status' => 404,
                'compNo' => null
            ]);
        }
    }

    public function getListOfComp(){
    
        $comps = CompetitorProfileCreation::where("delete_status", "=", "0")->get();
        if($comps){
        $compList = [];
        foreach($comps as $comp){
            $compList[] = ["value" => $comp['id'], "label" =>  $comp['compName']] ;
        }
        return  response()->json([
            'status' => 200,
            'compList' =>  $compList,
        ]);
    }
    else{
        return  response()->json([
            'status' => 404,
            'message' => "Not found",
        ]); 
    }
    }

    public function getLastCompno($stateid){

        $lastcompno = CompetitorProfileCreation::where('state',$stateid)
        ->select('compNo')
        ->orderBy('id','desc')
        ->first();
        // ->get();
            
        if ($lastcompno)
            return response()->json([
                'status' => 200,
                'lastcompno' => $lastcompno->compNo
            ]);
        else {
            return response()->json([
                'status' => 404,
                'lastcompno' => $lastcompno,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function CompetitorMasterTable(Request $request)
    {

    $user = Token::where('tokenid', $request->tokenid)->first();   
    $userid = $user['userid'];
    if($userid)
    {
            $tableName = 'competitor_profile_creations';
            $header=['Competitor No','Competitor Name','Mobile Number',' Email Id'];
            $specificColumns = ['compNo','compName','mobile','email'];
            $columnNames = DB::select("SHOW COLUMNS FROM $tableName");
            $filteredColumns = array_intersect($specificColumns, array_column($columnNames, 'Field'));
        $competitor = CompetitorProfileCreation::where('id','!=','')
                    ->select('id','compNo','compName','mobile','email') 
                    ->orderBy('id')
                    ->get();
                   
                        return response()->json([
                            'data'=>$competitor,
                            'header'=>$header,
                            'title'=>'Competitor Master',
                            'accessor'=> $filteredColumns,
                        ]);
                    }
    



    }
}
