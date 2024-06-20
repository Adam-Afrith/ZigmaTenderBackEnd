<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDetailsWorkOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class CompetitorDetailsWorkOrderController extends Controller
{

    public function store(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
if($user['userid'])
{
        if ($request->hasFile('woFile')) {

            $woFile = $request->woFile;
            $woFileExt = $woFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            $woFileName= $woFile->getClientOriginalName();
            // $woFileName1 = $woFile->hashName();
            // $woFilenameSplited = explode(".", $woFileName1);
            // if ($woFilenameSplited[1] != $woFileExt) {
            //     $woFileName = $woFilenameSplited[0] . "." . $woFileExt;
            // } else {
            //     $woFileName = $woFileName1;
            // }
            $woFile->storeAs('competitor/woFile', $woFileName, 'public');
           
            // $request->request->add(['cr_userid' => $user['userid']]);
            // $request->request->remove('tokenId');
            // $request->request->add(['woFileType' => $woFileExt]);
        }else {
            $woFileExt = '';
            $woFileName = '';
        }

            if ($request->hasFile('completionFile')) {

                $completionFile = $request->completionFile;
                $completionFileExt = $completionFile->getClientOriginalExtension();
                //received File extentions sometimes converted by browsers
                //Have to set orignal file extention before save
                // $completionFileName1 = $completionFile->hashName();
                $completionFileName = $completionFile->getClientOriginalName();
                // $completionFilenameSplited = explode(".", $completionFileName1);
                // if ($completionFilenameSplited[1] != $completionFileExt) {
                //     $completionFileName = $completionFilenameSplited[0] . "." . $completionFileExt;
                // } else {
                //     $completionFileName = $completionFileName1;
                // }
                $completionFile->storeAs('competitor/woCompletionFile', $completionFileName, 'public');
            } else {
                $completionFileExt = '';
                $completionFileName = '';
            }
            $existence = CompetitorDetailsWorkOrder::where("compNo", $request->compNo)
                ->where("compId", $request->compId)
                ->where("custName", $request->custName)->exists();

            if ($existence) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Certificate Name Already Exists!'
                ]);
            }

            // $validator = Validator::make($request->all(), ['compId' => 'required|integer', 'compNo' => 'required|string', 'custName' => 'required|string', 'projectName' => 'required|string', 'tnederId' => 'required|string', 'state' => 'required|string', 'woDate' => 'required|date', 'quantity' => 'required|string', 'unit' => 'required|string', 'projectValue' => 'required|string', 'perTonRate' => 'required|string', 'qualityCompleted' => 'required|string', 'date' => 'required|date', 'cr_userid' => 'required|integer']);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => 404,
            //         'message' => "Not able to Add details now..!",
            //         'error' => $validator->messages(),
            //     ]);
            // }
            /***************variable assing* */

if($request->projectName==null){
    $projectName='';
}
else{
    $projectName =$request->projectName;
}
if($request->tnederId==null){
    $tnederId='';
}
else{
    $tnederId =$request->tnederId;
}
if($request->state==null){
    $state='0';
}
else{
    $state =$request->state;
}
if($request->woDate==null){
    $woDate=NULL;
}
else{
    $woDate =$request->woDate;
}
if($request->quantity==null){
    $quantity='';
}
else{
    $quantity =$request->quantity;
}
if($request->unit==null){
    $unit='0';
}
else{
    $unit =$request->unit;
}
if($request->projectValue==null){
    $projectValue='';
}
else{
    $projectValue =$request->projectValue;
}
if($request->perTonRate==null){
    $perTonRate='';
}
else{
    $perTonRate =$request->perTonRate;
}
if($request->qualityCompleted==null){
    $qualityCompleted='';
}
else{
    $qualityCompleted =$request->qualityCompleted;
}
if($request->date==null){
    $date=NULL;
}
else{
    $date =$request->date;
}

            /************************************ */
            
            $datatostore = new CompetitorDetailsWorkOrder;
            $datatostore->compId = $request->compId;
            $datatostore->compNo = $request->compNo;
            $datatostore->projectName = $projectName;
            $datatostore->custName = $request->custName;
            $datatostore->tnederId = $tnederId;
            $datatostore->state = $state;
            $datatostore->woDate = $woDate;
            $datatostore->quantity = $quantity;
            $datatostore->unit = $unit;
            $datatostore->projectValue = $projectValue;
            $datatostore->perTonRate = $perTonRate;
            $datatostore->qualityCompleted = $qualityCompleted;
            $datatostore->date = $date;
            $datatostore->cr_userid = $user['userid'];
            $datatostore->woFile = $woFileName;
            $datatostore->woFileType = $woFileExt;
            $datatostore->completionFile = $completionFileName;
            $datatostore->completionFileType = $completionFileExt;
            $woRes = $datatostore->save();
            if ($woRes) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Added Succssfully!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Invalid Credentials..!'
            ]);
        }
    }


    public function show($id)
    {
        $wo = CompetitorDetailsWorkOrder::where('id',$id)
        ->select('*')
        ->first();
        
        
        
        if ($wo)
        {
            return response()->json([
                'status' => 200,
                'wo' => $wo
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
       
        $data = CompetitorDetailsWorkOrder::find($id); //to handle existing images 
        $datatostore =  CompetitorDetailsWorkOrder::findOrFail($id);

        if ($request->hasFile('woFile')  && !empty($request->woFile) && $request->woFile!= null) {
            //Update WO File
            $woFile = $request->woFile;
            $woFileExt = $woFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            // $woFileName1 = $woFile->hashName();
            $woFileName = $woFile->getClientOriginalName();
            // $woFilenameSplited = explode(".", $woFileName1);
            // if ($woFilenameSplited[1] != $woFileExt) {
            //     $woFileName = $woFilenameSplited[0] . "." . $woFileExt;
            // } else {
            //     $woFileName = $woFileName1;
            // }
              //to delete Existing Image from storage, if image Updated 
              if ($data['woFile']) {
                $image_path = public_path() . "/uploads/competitor/woFile/" . $data->woFile;
                unlink($image_path);
            }
            $woFile->storeAs('competitor/woFile', $woFileName, 'public');
            $datatostore['woFile'] = $woFileName;
            $datatostore['woFileType'] = $woFileExt;
        } else {
            if($request->woFile != null && $request->woFile== "")
            //if $request->woFile is null -> Has file for this record but no file is reupload
            //if $request->woFile is "" -> Has file for this record but file was reuploaded
            {   
                $image_path = public_path() . "/uploads/competitor/woFile/" . $data->woFile;
                unlink($image_path);
                $datatostore['woFile'] = "";
                $datatostore['woFileType'] = "";
            }
        }
        //Update completionFile
        if ($request->hasFile('completionFile') && !empty($request->completionFile)){
            // return "Has WoCompletion FIle   ---  ";
            $completionFile = $request->completionFile;
            $completionFileExt = $completionFile->getClientOriginalExtension();
            //received File extentions sometimes converted by browsers
            //Have to set orignal file extention before save
            // $completionFileName1 = $completionFile->hashName();
            $completionFileName = $completionFile->getClientOriginalName();
            // $completionFilenameSplited = explode(".", $completionFileName1);
            // if ($completionFilenameSplited[1] != $completionFileExt) {
            //     $completionFileName = $completionFilenameSplited[0] . "." . $completionFileExt;
            // } else {
            //     $completionFileName = $completionFileName1;
            // }
             //to delete Existing Image from storage, if image Updated
             if ($data['completionFile']) {
                $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
                unlink($image_path);
            }

            $completionFile->storeAs('competitor/woCompletionFile', $completionFileName, 'public');
            // $request->completionFile= $completionFileName;
            // $request->request->add(['completionFileType' => $completionFileExt]);
            $datatostore['completionFile'] = $completionFileName;
            $datatostore['completionFileType'] = $completionFileExt;
           
        } else {
            // echo "Not have WoCompletion FIle  ----    ";
            //  echo "Not have WoCompletion FIle  ----    ".$request->completionFile != null ." ---"; 
            //  echo "Not have WoCompletion FIle  ----    ". $request->completionFile =="". " ---";    
            // if($request->completionFile != null && $request->woFile== "") 
            if($request->completionFile != "null" && $request->completionFile =="") 
            {  
                // return "Reached Here";
                $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
                unlink($image_path);
                $datatostore['completionFile'] = "";
                $datatostore['completionFileType'] = "";
            }
            // else{
            //     if($request->completionFile =="null")
            //     {
            //         echo "null  --";
            //     }
            //     else{
            //         echo "Not Null  --- ";
            //     }
            //     if($request->completionFile =="")
            //     {
            //         echo "Empty -----";
            //     }
            //     else{
            //         echo "Not Empty -- ";
            //     }

            //     if($request->woFile == "null")
            //     {
            //         echo "woFile null  --";
            //     }
            //     else{
            //         echo "woFile Not Null  --- ";
            //     }
            //     if($request->woFile ==="")
            //     {
            //         echo "woFile Empty -----";
            //     }
            //     else{
            //         echo "woFile Not Empty -- ";
            //     }
            //     return " -- ";
            // }
           
        }

        $user = Token::where("tokenid", $request->tokenId)->first();
        $request->request->add(['edited_userid' => $user['userid']]);
        // $validator = Validator::make($request->all(), ['compId' => 'required|integer', 'compNo' => 'required|string', 'custName' => 'required|string', 'projectName' => 'required|string', 'tnederId' => 'required|string', 'state' => 'required|integer', 'woDate' => 'required|date', 'quantity' => 'required|string', 'unit' => 'required|integer', 'projectValue' => 'required|string', 'perTonRate' => 'required|string', 'qualityCompleted' => 'required|string', 'date' => 'required|date', 'edited_userid' => 'required|integer']);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => "Not able to update details now..!",
        //         'error' => $validator->messages(),
        //     ]);
        // }

        // $datatostore =  CompetitorDetailsWorkOrder::findOrFail($id);  // declared at the top to set files
 /***************variable assing* */

 if($request->projectName==null){
    $projectName='';
}
else{
    $projectName =$request->projectName;
}
if($request->tnederId==null){
    $tnederId='';
}
else{
    $tnederId =$request->tnederId;
}
if($request->state==null){
    $state='0';
}
else{
    $state =$request->state;
}
if($request->woDate==null ||$request->woDate=='null'){
    $woDate=NULL;
}
else{
    $woDate =$request->woDate;
}

if($request->quantity==null){
    $quantity='';
}
else{
    $quantity =$request->quantity;
}
if($request->unit==null){
    $unit='0';
}
else{
    $unit =$request->unit;
}
if($request->projectValue==null){
    $projectValue='';
}
else{
    $projectValue =$request->projectValue;
}
if($request->perTonRate==null){
    $perTonRate='';
}
else{
    $perTonRate =$request->perTonRate;
}
if($request->qualityCompleted==null){
    $qualityCompleted='';
}
else{
    $qualityCompleted =$request->qualityCompleted;
}
if($request->date==null ||$request->date=='null'){
    $date=NULL;
}
else{
    $date =$request->date;
}

            /************************************ */


        $datatostore['compId'] = $request->compId;
        $datatostore['compNo'] = $request->compNo;
        $datatostore['projectName'] = $projectName;
        $datatostore['custName'] = $request->custName;
        $datatostore['tnederId'] = $tnederId;
        $datatostore['state'] = $state;
        $datatostore['woDate'] = $woDate;
        $datatostore['quantity'] = $quantity;
        $datatostore['unit'] = $unit;
        $datatostore['projectValue'] = $projectValue;
        $datatostore['perTonRate'] = $perTonRate;
        $datatostore['qualityCompleted'] = $qualityCompleted;
        $datatostore['date'] = $date;
        $datatostore['edited_userid'] = $user['userid'];
        $woedit = $datatostore->save();

        if ($woedit)
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
        try {

            //to delete Existing Image from storage
            $data = CompetitorDetailsWorkOrder::find($id);
            //to delete Existing Image from storage
            $data = CompetitorDetailsWorkOrder::find($id);
            if($data->completionFile){
                $image_path = public_path() . "/uploads/competitor/woCompletionFile/" . $data->completionFile;
                unlink($image_path);
    
            }
            if($data->woFile){
                $image_path = public_path() . "/uploads/competitor/woFile/" . $data->woFile;
                unlink($image_path);
            }
            
          

            $wo = CompetitorDetailsWorkOrder::destroy($id);
            if ($wo) {
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



    public function getWOList($compid)
    {
        $wo = CompetitorDetailsWorkOrder::where("compId", $compid)
            ->select('competitor_details_work_orders.*', 'state_masters.state_name', 'unit_masters.unit_name')
            ->leftjoin('state_masters', 'state_masters.id', 'competitor_details_work_orders.state')
            ->leftjoin('unit_masters', 'unit_masters.id', 'competitor_details_work_orders.unit')
            ->get();
        if ($wo) {
            return response()->json([
                'status' => 200,
                'wo' => $wo
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    public function download($id, $type)
    {
        $doc = CompetitorDetailsWorkOrder::where('id', $id)
            ->select($type == "woCompletionFile" ? "completionFile" : $type)
            ->get();

        if (!empty($doc[0][$type == "woCompletionFile" ? "completionFile" : $type])) {
            $ext = explode(".", ($doc[0][$type == "woCompletionFile" ? "completionFile" : $type]));
            $contentType = $this->getFileType($ext[1]);
            $file = public_path() . "/uploads/competitor/$type/" . $doc[0][$type == "woCompletionFile" ? "completionFile" : $type];
            
            return response()->download($file, $doc[0][$type == "woCompletionFile" ? "completionFile" : $type], ['Content-Type' => $contentType]);    

        } else {
            return response()->json([
                'file' => 'File not found.'
            ], 204);
        }
    }

    public function getFileType($type)
    {
        // Get the file path based on the type
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'webp' => 'image/webp',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'rar' => 'application/x-rar-compressed',
            'zip' => 'application/zip',
        ];
    
        return $mimeTypes[$type] ?? 'application/octet-stream';
    }
}
