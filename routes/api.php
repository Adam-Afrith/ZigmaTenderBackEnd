<?php

use App\Http\Controllers\UlbMasterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllerTemp;
use App\Http\Controllers\StateMasterController;
use App\Http\Controllers\CountryMasterController;
use App\Http\Controllers\UnitMasterController;
use App\Http\Controllers\CityMasterController;
use App\Http\Controllers\DistrictMasterController;
use App\Http\Controllers\CustomerCreationMainController;
use App\Http\Controllers\CustomerCreationProfileController;
use App\Http\Controllers\CustomerCreationContactPersonController;
use App\Http\Controllers\CustomerCreationSWMProjectStatusController;
use App\Http\Controllers\CompetitorProfileCreationController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\CustomerSubCategoryController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\ULBDetailsController;
use App\Http\Controllers\CompetitorDetailsBranchesController;
use App\Http\Controllers\CustomerCreationBankDetailsController;
use App\Http\Controllers\BidCreationCreationController;
use App\Http\Controllers\CompetitorDetailsTurnOverController;
use App\Http\Controllers\CompetitorDetailsCompanyNetWorthController;
use App\Http\Controllers\CompetitorDetailsLineOfBusinessController;
use App\Http\Controllers\BidCreationCreationDocsController;
use App\Http\Controllers\CompetitorDetailsProsConsController;
use App\Http\Controllers\TenderTypeMasterController;
use App\Http\Controllers\CompetitorDetailsQualityCertificatesController;
use App\Http\Controllers\TenderCreationController;
use App\Http\Controllers\CompetitorDetailsWorkOrderController;
use App\Http\Controllers\AttendanceTypeMasterController;
use App\Http\Controllers\BidManagementWorkOrderMobilizationAdvanceController;
use App\Http\Controllers\BidManagementWorkOrderProjectDetailsController;
use App\Http\Controllers\BidManagementWorkOrderWorkOrderController;
use App\Http\Controllers\BidManagementWorkOrderCommunicationFilesController;
use App\Http\Controllers\BidManagementWorkOrderLetterOfAcceptenceController;
use App\Http\Controllers\BidManagementTenderStatusBiddersController; // replaced this by TenderStatusBiddersController
use App\Http\Controllers\TenderStatusBiddersController; // currently used Controller
use App\Http\Controllers\TenderStatusTechEvaluationController;
use App\Http\Controllers\BidmanagementPreBidQueriesController;
use App\Http\Controllers\BidmanagementCorrigendumPublishController;
use App\Http\Controllers\BidCreationTenderParticipationController;
use App\Http\Controllers\BidCreationTenderFeeController;
use App\Http\Controllers\BidCreationEMDController;
use App\Http\Controllers\BidCreationBidSubmittedStatusController;
use App\Http\Controllers\FileDownloadHandlingController;
use App\Http\Controllers\TenderStatusFinancialEvaluationsController;
use App\Http\Controllers\TenderStatusContractAwardedController;
use App\Http\Controllers\BidManagementTenderOrBidStausController;
use App\Http\Controllers\CommunicationfilesmasterController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
// use App\Models\CompetitorDetailsWorkOrder;
use App\Http\Controllers\CallTypeController;
use App\Http\Controllers\CalltobdmController;
use App\Http\Controllers\ZoneMasterController;
use App\Http\Controllers\BusinessForecastController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\AttendanceEntryController;
use App\Http\Controllers\AttendanceTypeController;
use App\Http\Controllers\CallCreationController;
use App\Http\Controllers\BusinessForecastStatusController;
use App\Http\Controllers\CallLogFilesController;
use App\Http\Controllers\OtherExpenseSubController;
use App\Http\Controllers\OtherExpenseController;
use App\Http\Controllers\CallHistoryController;
use App\Http\Controllers\DayWiseReportController;
use App\Http\Controllers\AttendanceRegisterController;
use App\Http\Controllers\ExpensesApprovalController;
use App\Http\Controllers\OtherExpensesController;
use App\Http\Controllers\HolidaysController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login1', [UserControllerTemp::class, 'login1']);
Route::post('validtetoken', [UserControllerTemp::class, 'validateToken']);
Route::post('getrolesandpermision', [UserControllerTemp::class, 'getRolesAndPermissions']);

Route::post('logout', [UserControllerTemp::class, 'logout']);
Route::post('createState', [UserControllerTemp::class, 'login1']);


// Route::get('customer/list', [CustomerCreationMainController::class, 'getList']);



// Route::get('state/list/{id}', [StateMasterController::class, 'getStateList']);

// Route::get('tendertype/{id}', [TenderTypeMasterController::class, 'show']);

// Route::get('state/list/{id}/{category}/{savedstate}', [StateMasterController::class, 'getStateListOptions']);

Route::post('customercreationmain/getmainid', [CustomerCreationMainController::class, 'getMainid']);
Route::post('customercreation/profile', [CustomerCreationProfileController::class, 'getProfileFromData']);

Route::post('customer/list', [CustomerCreationProfileController::class, 'getList']);


// Route::get('customercreation/contact/getFormNo', [CustomerCreationContactPersonController::class, 'getFormNo']);


Route::post('customercreationsmwprojectstatus/getlist', [CustomerCreationSWMProjectStatusController::class, 'getlist']);

Route::post('bidcreation/creation/docupload/list', [BidCreationCreationDocsController::class, 'getUplodedDocList']);
Route::post('bidcreation/creation/docupload/{id}', [BidCreationCreationDocsController::class, 'update']);



// Route::post('competitordetails/competitorqcertificate/updatewithimage'

Route::post('bidcreation/creation/bidlist', [BidCreationCreationController::class, 'getBidList']);




Route::post('bidcreation/prebidqueries/docupload/list', [BidmanagementPreBidQueriesController::class, 'getUplodedDocList']);

Route::post('bidcreation/prebidqueries/docupload/{id}', [BidmanagementPreBidQueriesController::class, 'update']);



Route::post('tenderstatus/updatestatus/{id}', [BidManagementTenderStatusBiddersController::class, 'updateStatus']);

Route::post('bidcreation/corrigendumpublish/docupload/list', [BidmanagementCorrigendumPublishController::class, 'getUplodedDocList']);

Route::post('bidcreation/corrigendumpublish/docupload/{id}', [BidmanagementCorrigendumPublishController::class, 'update']);
//brindha updated on 21-01-2023









// Route::post('bidcreation/getWorkList/list', [BidmanagementCorrigendumPublishController::class, 'getWorkList']);

Route::post('workorder/creation/Workorder/update/{workid}', [BidManagementWorkOrderWorkOrderController::class, 'update']);

Route::post('download/files', [FileDownloadHandlingController::class, 'download']);


Route::post('letteracceptance/creation/update/{id}', [BidManagementWorkOrderLetterOfAcceptenceController::class, 'update']);

Route::post('/workorder/creation/communicationfiles/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'store']);
Route::post('/workorder/creation/communicationfileUpload', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfileUpload']);
Route::post('/workorder/creation/communicationfileUploadlist', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfileUploadlist']);









Route::post('/legacystatement', [BidCreationCreationController::class, 'getlegacylist']);




Route::post('/tendertrack/creation/tracklist', [TenderCreationController::class, 'gettrackList']);


Route::post('tenderstatus/bidderstenderstatus/{id}', [TenderStatusBiddersController::class, 'BiddersTenderStatus']);



Route::put('tenderstatus/techevaluation/{id}', [TenderStatusTechEvaluationController::class, 'update']);





Route::post('communicationfilesmaster/list', [CommunicationfilesmasterController::class, 'docList']);
Route::delete('communicationfilesmaster/deletedoc/{id}', [CommunicationfilesmasterController::class, 'deletefile']);




Route::post('ulbreport/ulblist', [ULBDetailsController::class, 'getulbreport']);//ulb report page  
Route::post('ulbreport/populb', [ULBDetailsController::class, 'setpopupUlb']);//ulb popup page  



Route::post('usertype', [UserTypeController::class, 'store']);


// Route::get('userOptions', [UserControllerTemp::class, 'getoptions']);





Route::post('getbdmdetails', [UserControllerTemp::class, 'getbdmdetails']); // Collecting particular BDM User details, to dispaly BDM name and All
Route::post('filteredcustomerlist', [CustomerCreationProfileController::class, 'getFilteredCustomerList']);



Route::put('usertype/{id}', [UserTypeController::class, 'update']);
Route::delete('usertype/{id}', [UserTypeController::class, 'destroy']);




Route::post('setpermission', [PermissionController::class, 'store']);

Route::delete('userpermission/{role_id}', [PermissionController::class, 'destroy']);




Route::post('callupload', [CallCreationController::class, 'callfileupload']);



Route::post('callcreation/callnolist', [CallCreationController::class, 'usersCallList']);





Route::POST("calltobdm/updateAssignedCustomer",[CalltobdmController::class,'updateAssignedCustomer']);
Route::post('getdaywisereport/list',[DayWiseReportController::class,'getDayWiseReport']);


//attendanceregisterroutes
Route::post('attendanceregister/entrylist',[AttendanceRegisterController::class,'userbasedindex']);
// Route::post('attendanceregister',[AttendanceRegisterController::class,'store']);
 Route::post('attendanceregister/fileList',[AttendanceRegisterController::class,'getFilesList']);
// Route::get('attendanceregister/{id}',[AttendanceRegisterController::class,'show']);
// Route::put('attendanceregister/{id}', [AttendanceRegisterController::class,'update']);
// Route::delete('attendanceregister/{id}',[AttendanceRegisterController::class,'destroy']);

Route::post('attendance/docdownload', [AttendanceRegisterController::class, 'download']);
Route::delete('destroyfile/{id}',[AttendanceRegisterController::class,'destroyFile']);
Route::post('getempleave/list',[AttendanceRegisterController::class,'getEmployeeLeaveList']);//For Attendance Report






/*********************************
 * other expesive Naveen
 */
/******************************* */

/*********************************
 * Expenses type
 */

// Route::get('otherexpsubfiledownload/{id}/{fileName}', [OtherExpenseSubController::class, 'download']);


// Route::get('callcreation/getCallMainList/{token}', [CallCreationController::class, 'getCallMainList']);     
Route::post('fileupload/{id}', [ExpenseTypeController::class, 'Fileupload']);
Route::post('expenseshowupdate/{id}',[ExpenseTypeController::class,'Expenseshowupdate']);
Route::delete('expensedestroy/{id}',[ExpenseTypeController::class,'Expensedestroy']);

/*** Other Expesive   */

Route::post('expenses/staffList', [OtherExpensesController::class, 'get_staff_name_limits']);
Route::post('/expenseinv', [OtherExpensesController::class, 'ExpInvoice']);

Route::post('/finalSubmit', [OtherExpensesController::class, 'finalSubmit']);
Route::post('/expensesub', [OtherExpensesController::class, 'ExpSub']);

Route::post('/editsub', [OtherExpensesController::class, 'EditSub']);
Route::post('otherExpcustomer/list', [OtherExpensesController::class, 'getList']);
Route::post('callnumber', [OtherExpensesController::class, 'CallNumber']);
Route::post('/getlimit', [OtherExpensesController::class, 'lmitAmount']);
Route::post('/expensestore', [OtherExpensesController::class, 'Expensestore']);
Route::post('/subupdate', [OtherExpensesController::class, 'SubUpdate']);
Route::post('/mainlist', [OtherExpensesController::class, 'Mainlist']);
Route::delete('deleteMain/{id}',[OtherExpensesController::class,'deleteMain']);
// Route::get('downloadfile/{filename}', [OtherExpensesController::class,'downloadFile']);
Route::post('/expense/downloadfile', [OtherExpensesController::class,'downloadFile']);
/*** Rembusment*/

Route::post('expensesapp/expapp', [ExpensesApprovalController::class, 'index']);
Route::post('expensesapp/staffList', [ExpensesApprovalController::class, 'get_staff_name']);
Route::post('expensesapp/getsublist', [ExpensesApprovalController::class, 'showsub']);
Route::post('expensesapp/storeData', [ExpensesApprovalController::class, 'store']);
Route::post('expensesapp/popupsub', [ExpensesApprovalController::class, 'popupsub']);
Route::post('expensesapp/UpdateApproval', [ExpensesApprovalController::class, 'UpdateApproval']);
Route::post('expensesapp/printView', [ExpensesApprovalController::class, 'PrintView']);
/**** */





//Middleware
Route::middleware(['token.auth'])->group(function(){
    Route::post('districtmastertable',[DistrictMasterController::class,'DistrictMasterTable']);
    Route::post('citymastertable',[CityMasterController::class,'CityMasterTable']);
    Route::post('statemastertable',[StateMasterController::class,'StateMasterTable']);
    Route::post('countrymastertable',[CountryMasterController::class,'CountryMasterTable']);
    Route::post('competitormastertable',[CompetitorProfileCreationController::class,'CompetitorMasterTable']);
    Route::post('customercreationmaster',[CustomerCreationProfileController::class,'CustomerCreationMaster']);
    Route::post('permissionmastertable',[PermissionController::class,'PermissionMasterTable']);
    Route::post('usermastertable',[UserControllerTemp::class,'UserMasterTable']);
    Route::post('usertypemaster',[UserTypeController::class,'UserTypeMaster']);
    Route::post('bidcreationmaster',[BidCreationCreationController::class,'BidCreationMaster']);
    Route::post('ulbdetailsmaster',[ULBDetailsController::class,'ULBDetailsMaster']);
    Route::post('attendancemaster',[AttendanceRegisterController::class,'AttendanceMaster']);
    Route::post('attendancemasterreport',[AttendanceRegisterController::class,'AttendanceMasterReport']);
    Route::post('holidaymaster',[HolidaysController::class,'HolidayMaster']);
    Route::post('expensesapprovalmaster',[ExpensesApprovalController::class,'ExpensesApprovalMaster']);


    //ReactDataTable - APIs


    Route::post('unitstable', [UnitMasterController::class, 'UnitMasterTable']);
    Route::post('projecttypetable', [ProjectTypeController::class, 'ProjectTypeTable']);
    Route::post('projectstatustable', [ProjectStatusController::class, 'ProjectStatusTable']);
    Route::post('customersubcategorytable', [CustomerSubCategoryController::class, 'CustSubCatTable']);
    Route::post('tendertypestable', [TenderTypeMasterController::class, 'TenderTypesTable']);
    Route::post('zonetable', [ZoneMasterController::class, 'ZoneMasterTable']);
    Route::post('bizzforecasttable', [BusinessForecastController::class, 'BizzForecastTable']);
    Route::post('expensetypetable', [ExpenseTypeController::class, 'ExpenseTypeTable']);
    Route::post('calltypetable', [CallTypeController::class, 'CallTypeTable']);
    Route::post('attendancetypetable', [AttendanceTypeController::class, 'AttendanceTypeTable']);

    Route::post('bidlisttable', [BidCreationCreationController::class, 'BidManagementListTable']);
    Route::post('tendertrackertable', [TenderCreationController::class, 'TenderTrackerTable']);

    Route::post('communicationfilestable', [CommunicationfilesmasterController::class, 'CommunicationFilesTable']);

    Route::post('callbookingtable', [CallCreationController::class, 'CallBookTable']);
    Route::post('calltobdmtable',[UserControllerTemp::class,'BDMOptionsTable']);
    Route::post('assigncallstable', [CustomerCreationProfileController::class, 'AssignCallsTable']);
    Route::post('callreportstable',[DayWiseReportController::class,'CallReportTable']);

    Route::post('otherexpensestable', [OtherExpensesController::class, 'OtherExpTable']);

///////////////////////////////ReactTable API Ends/////////////////////////////////////////////////////////
Route::get('sendmail',[BidCreationCreationController::class,'mailstatus']);
Route::middleware(['token.auth'])->group(function(){
    Route::post('districtmastertable',[DistrictMasterController::class,'DistrictMasterTable']);
    Route::post('citymastertable',[CityMasterController::class,'CityMasterTable']);
    Route::post('statemastertable',[StateMasterController::class,'StateMasterTable']);
    Route::post('countrymastertable',[CountryMasterController::class,'CountryMasterTable']);
    Route::post('competitormastertable',[CompetitorProfileCreationController::class,'CompetitorMasterTable']);
    Route::post('customercreationmaster',[CustomerCreationProfileController::class,'CustomerCreationMaster']);
    Route::post('permissionmastertable',[PermissionController::class,'PermissionMasterTable']);
    Route::post('usermastertable',[UserControllerTemp::class,'UserMasterTable']);
    Route::post('usertypemaster',[UserTypeController::class,'UserTypeMaster']);
    Route::post('bidcreationmaster',[BidCreationCreationController::class,'BidCreationMaster']);
    Route::post('ulbdetailsmaster',[ULBDetailsController::class,'ULBDetailsMaster']);
    Route::post('attendancemaster',[AttendanceRegisterController::class,'AttendanceMaster']);
    Route::post('attendancemasterreport',[AttendanceRegisterController::class,'AttendanceMasterReport']);
    Route::post('holidaymaster',[HolidaysController::class,'HolidayMaster']);
    Route::post('expensesapprovalmaster',[ExpensesApprovalController::class,'ExpensesApprovalMaster']);
// gettopost 7-06-2023
Route::post('usertype', [UserTypeController::class, 'index']);
Route::post('usertype/{id}', [UserTypeController::class, 'show']);
Route::post('usertypeoption', [UserTypeController::class, 'getoptions']);
Route::post('userpermissions', [PermissionController::class, 'getPermissionList']);
Route::post('usertypeOptionsForPermission', [PermissionController::class, 'getoptions']);
Route::post('menu/options', [MenuController::class, 'getoptions']);
Route::post('customersubcategory/list/{profileid}', [CustomerSubCategoryController::class, 'getList']);
Route::post('country/list/{savedcountry}', [CountryMasterController::class, 'getListofcountry']);
// Route::post('state/list/{id}/{category}/{savedstate}', [StateMasterController::class, 'getStateListOptions']);
Route::post('district/list/{countryid}/{stateid}/{saveddistrict}', [DistrictMasterController::class, 'getDistrictListofstate']);
Route::post('city/list/{countryid}/{stateid}/{districtid}/{savedcity}', [CityMasterController::class, 'getCityList']);
Route::post('customercreationcontact/getlist', [CustomerCreationContactPersonController::class, 'getlist']);
Route::post('customercreationbankdetails/getlist', [CustomerCreationBankDetailsController::class, 'getlist']);
Route::post('country/list', [CountryMasterController::class, 'getList']);
// gettopost 08-06-2023
Route::post('state/list/{id}', [StateMasterController::class, 'getStateList']);
Route::post('district/list/{countryid}/{stateid}', [DistrictMasterController::class, 'getDistrictList']);
Route::post('competitorprofile/getcompno/{compid}', [CompetitorProfileCreationController::class, 'getCompNo']);
Route::post('competitorbranch/branchlist/{compid}', [CompetitorDetailsBranchesController::class, 'getbranchList']);
Route::post('competitordetails/turnoverlist/{compid}', [CompetitorDetailsTurnOverController::class, 'getTurnOverList']);
Route::post('competitordetails/networthlist/{compid}', [CompetitorDetailsCompanyNetWorthController::class, 'getNetWorthList']);
Route::post('competitordetails/qclist/{compid}', [CompetitorDetailsQualityCertificatesController::class, 'getQCList']);
Route::post('competitordetails/lineofbusinesslist/{compid}', [CompetitorDetailsLineOfBusinessController::class, 'getLineOfBusinessList']);
Route::post('unit/list', [UnitMasterController::class, 'getunitList']);
Route::post('competitordetails/wolist/{compid}', [CompetitorDetailsWorkOrderController::class, 'getWOList']);
Route::post('competitordetails/prosconslist/{compid}', [CompetitorDetailsProsConsController::class, 'getProsConsList']);
});

// Ashiq

    Route::post('userlist', [AttendanceRegisterController::class,'UserList']);
    Route::post('attendancefile/{id}/{fileName}', [AttendanceRegisterController::class, 'download']);

    Route::post('employeelist', [UserControllerTemp::class, 'getEmployeeList']);
    Route::post('usertype', [UserTypeController::class, 'index']);
    Route::post('country/list', [CountryMasterController::class, 'getList']);
    Route::post('bdmlist', [UserControllerTemp::class, 'getBdmList']);
    Route::post('state/list/{id}', [StateMasterController::class, 'getStateList']);
    Route::post('state/list/{id}/{category}/{savedstate}', [StateMasterController::class, 'getStateListOptions']);
    Route::post('state-list/{id}', [StateMasterController::class, 'getStates']);
    Route::post('district/list/{countryid}/{stateid}', [DistrictMasterController::class, 'getDistrictList']);
    Route::post('district/list/{countryid}/{stateid}/{saveddistrict}', [DistrictMasterController::class, 'getDistrictListofstate']);
    Route::post('city/list/{countryid}/{stateid}/{districtid}/{savedcity}', [CityMasterController::class, 'getCityList']);
    Route::post('competitorprofile/getcompno/{compid}', [CompetitorProfileCreationController::class, 'getCompNo']);
    Route::post('competitorbranch/branchlist/{compid}', [CompetitorDetailsBranchesController::class, 'getbranchList']);
    Route::post('competitordetails/turnoverlist/{compid}', [CompetitorDetailsTurnOverController::class, 'getTurnOverList']);
    Route::post('competitordetails/networthlist/{compid}', [CompetitorDetailsCompanyNetWorthController::class, 'getNetWorthList']);
    Route::post('competitordetails/lineofbusinesslist/{compid}', [CompetitorDetailsLineOfBusinessController::class, 'getLineOfBusinessList']);
    Route::post('competitordetails/prosconslist/{compid}', [CompetitorDetailsProsConsController::class, 'getProsConsList']);
    Route::post('competitordetails/qclist/{compid}', [CompetitorDetailsQualityCertificatesController::class, 'getQCList']);
    Route::post('competitordetails/wolist/{compid}', [CompetitorDetailsWorkOrderController::class, 'getWOList']);
    Route::post('download/userfile/{id}', [UserControllerTemp::class, 'getdocs']);
    Route::post('/competitorprofile/getlastcompno/{id}', [CompetitorProfileCreationController::class, 'getLastCompno']);
    Route::post('/download/competitorqcertificate/{id}', [CompetitorDetailsQualityCertificatesController::class, 'download']);
    Route::post('/download/competitorworkorder/{id}/{type}', [CompetitorDetailsWorkOrderController ::class, 'download']);
    Route::post('/customercreation/getstatecode/{id}', [StateMasterController::class, 'getStateCode']);
    Route::post('tenderstatus/complist', [CompetitorProfileCreationController::class, 'getListOfComp']);
    Route::post('usertype/options', [UserTypeController::class, 'getoptions']);


/////////////////////////////////////////////////////////////////////////////////////////////////

//Afrith

    // Masters
    Route::post('unit/list', [UnitMasterController::class, 'getunitList']);
    Route::post('unitmasters/getUnitList', [UnitMasterController::class, 'getListofUnits']);
    // Route::post('unit', [UnitMasterController::class, 'index']);
    Route::post('projecttype/list/{profileid}', [ProjectTypeController::class, 'getList']);
    Route::post('projecttype/list', [ProjectTypeController::class, 'getListofProjectType']);
    Route::post('projectstatus/list/{profileid}', [ProjectStatusController::class, 'getList']);
    Route::post('customersubcategory/list/{profileid}', [CustomerSubCategoryController::class, 'getList']);
    Route::post('tendertype/list', [TenderTypeMasterController::class, 'getList']);
    Route::post('tendercreation/list', [TenderTypeMasterController::class, 'getList']);
    Route::post('expensetype/list', [ExpenseTypeController::class, 'getExpenseTypeList']);
    Route::post('customernamelist', [ExpenseTypeController::class, 'customerNameList']);
    Route::post('expansetypelist/{expid}', [ExpenseTypeController::class, 'ExpanseTypeList']);
    Route::post('expenseshow/{id}',[ExpenseTypeController::class,'Expenseshow']);
    Route::post('calltype/list',[CallTypeController::class, 'getCallTypeList']);
    Route::post('attendancetypelist', [AttendanceTypeController::class,'getAttendanceTypeList']);

    //Tenders
    Route::post('bidcreation/creation/live_tenders', [BidCreationCreationController::class, 'live_tender']);
    Route::post('bidcreation/creation/fresh_tenders', [BidCreationCreationController::class, 'fresh_tender']);
    Route::post('bidcreation/creation/awarded_tenders', [BidCreationCreationController::class, 'awarded_tenders']);
    Route::post('/bidcreation/creation/getlastbidno/{id}', [BidCreationCreationController::class, 'getLastBidno']);
    Route::post('bidcreation/creation/projectstatus', [BidCreationCreationController::class, 'projectstatus']);// returns running  & completed projects count for dashboard

    Route::post('tendercreation/list/{id}', [TenderCreationController::class, 'getTenderList']);//not working
    Route::post('tendercreation-list/{id}', [TenderCreationController::class, 'getTender']);//not working
    Route::post('/tendertrack/list', [TenderCreationController::class, 'gettendertrack']);

    //Library
    Route::post('download/communicationfilesmaster/{id}', [CommunicationfilesmasterController::class, 'download']);

    //CallLogs
    Route::post('/dashboard/getCallCountAnalysis', [CallCreationController::class, 'getCallCountAnalysis']);//Dashborad contents based on bdmcalldetails
    Route::post('bizzlist/list/{id}', [CallCreationController::class, 'getBizzList']);
    Route::post('statuslist/list/{id}', [CallCreationController::class, 'getStatusList']);
    Route::post('calldownload/{id}/{fileName}', [CallCreationController::class, 'download']);
    Route::post('user/list', [CallCreationController::class, 'getUserList']);
    Route::post('procurementlist/list', [CallCreationController::class, 'getProcurementList']);
    Route::post('callcreation/getCallMainList/{token}', [CallCreationController::class, 'getCallMainList']);

    //OtherExpense
    Route::post('/updatedl/{id}', [OtherExpensesController::class, 'GetDel']);
    Route::post('/otherexpensesubdel/{id}', [OtherExpensesController::class, 'Expensedestroy']);
    Route::post('otherexpsubfiledownload/{id}/{fileName}', [OtherExpenseSubController::class, 'download']); //No results found


    //OtherAPIs
    Route::post('ulb-list/{savedulb}', [CustomerCreationProfileController::class, 'getUlbs']);
    Route::post('customercreation/getcustno/{stateid}', [CustomerCreationProfileController::class, 'getCustNo']);
    Route::post('customercreation/profile/getFormNo', [CustomerCreationProfileController::class, 'getFormNo']);
    Route::post('customerOptions', [CustomerCreationProfileController::class, 'getOptions']);

    //CallLogFiles
    Route::post('callcreation/doclist/{id}', [CallLogFilesController::class, 'getUplodedDocList']);
    Route::post('callcreation/docdownload/{id}', [CallLogFilesController::class, 'download']);
    Route::post('dashboard/callcount', [CallLogFilesController::class, 'getCallCounts']);
    //CallHistory
    Route::post("getcallhistory/list/{id}",[CallHistoryController::class,'getCallHistory']);


    //AllBids
    Route::post('download/BidDocs/{fileName}', [BidCreationCreationDocsController::class, 'download']);
    Route::post('tenderstatus/getbidder/{id}', [BidManagementTenderStatusBiddersController::class, 'getBidders']);
    Route::post('workorder/getComList/{comId}', [BidManagementWorkOrderCommunicationFilesController::class, 'getComList']);
    Route::post('/workorder/creation/communicationfiledelete/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfiledelete']);
    Route::post('/competitordetails/commFilesList/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'getComList']);
    Route::post('moilization/getMobList/{mobId}', [BidManagementWorkOrderMobilizationAdvanceController::class, 'getMobList']);
    Route::post('ProjectDetails/getProList/{proid}', [BidManagementWorkOrderProjectDetailsController::class, 'getProList']); 
    Route::post('download/workorderimage/{woid}', [BidManagementWorkOrderWorkOrderController::class, 'wodownload']);
    Route::post('download/agreementimage/{agid}', [BidManagementWorkOrderWorkOrderController::class, 'agdownload']);
    Route::post('download/sitehandoverimage/{shoid}', [BidManagementWorkOrderWorkOrderController::class, 'shodownload']);
    Route::post('workorder/creation/Workorder/getimagename/{workid}', [BidManagementWorkOrderWorkOrderController::class, 'getimagename']);
    Route::post('download/prebidqueriesdocs/{fileName}', [BidmanagementPreBidQueriesController::class, 'download']);
    Route::post('download/corrigendumpublishdocs/{fileName}', [BidmanagementCorrigendumPublishController::class, 'download']);
    Route::post('download/tenderfeedocs/{id}', [BidCreationTenderFeeController::class, 'getdocs']);
    Route::post('download/emdfeedocs/{id}', [BidCreationEMDController::class, 'getdocs']);
    Route::post('download/bidsubmittedstatusdocs/{id}', [BidCreationBidSubmittedStatusController::class, 'getdocs']);
    Route::post('download/letterofacceptance/workorderimage/{woid}', [BidManagementWorkOrderLetterOfAcceptenceController::class, 'wodownload']);
    Route::post('download/BidManagementTenderOrBidStausDocs/{id}', [BidManagementTenderOrBidStausController::class, 'getdocs']);
    

    Route::post('bidmanagement/tenderstatus/acceptedbidders/{id}', [TenderStatusBiddersController::class, 'getAcceptedBidders']);

    Route::post('technicalevalution/qualifiedlist/{id}', [TenderStatusTechEvaluationController::class, 'getQualifiedList']);
    Route::post('tenderstatus/techevaluation/{id}', [TenderStatusTechEvaluationController::class, 'getTechEvaluationList']);
    Route::post('/tenderstatus/techevaluation/download/{id}', [TenderStatusTechEvaluationController::class, 'download']);

    Route::post('/tenderstatus/awardontract/download/{id}', [TenderStatusContractAwardedController::class, 'download']);

    Route::post('/tenderstatus/financialevaluation/getleastbidder/{id}', [TenderStatusFinancialEvaluationsController::class, 'getleastbidder']);
    Route::post('financialevaluation/getstoreddata/{id}',[TenderStatusFinancialEvaluationsController::class,'getStoredFinEvalData']);

    Route::post('/dashboard/ulbdetails', [ULBDetailsController::class, 'getulbyearlydetails']);//Dashborad contents based on ulbdetails
    Route::post('/dashboard/bidanalysis', [ULBDetailsController::class, 'getbidanalysis']);//Dashborad contents based on ulbdetails
    Route::post('/dashboard/tenderanalysis', [ULBDetailsController::class, 'tenderanalysis']);//Dashborad contents based on ulbdetails
    Route::post('/dashboard/ulbpopdetails', [ULBDetailsController::class, 'getulbpopulationdetails']);//Dashborad contents based on ulbdetails

    Route::post('state/zonefilteredlist/{cid}/{id}', [StateMasterController::class, 'getZoneFilteredStateList']);//no results found

    Route::post('rolelist', [UserTypeController::class, 'getRoleList']);//no results found
    Route::post('bdmoptions', [UserControllerTemp::class, 'getBdmUsersList']);
    Route::post('usertype/{id}', [UserTypeController::class, 'show']);
    Route::post('menus', [MenuController::class, 'getMenus']);
    Route::post('menu/options', [MenuController::class, 'getoptions']);
    Route::post('rolehaspermission/{tokenid}', [UserControllerTemp::class, 'getRolehasPermission']);//no results found
    Route::post('userpermissions', [PermissionController::class, 'getPermissionList']);
    Route::post('permisions/{usertype}', [PermissionController::class, 'getSavedData']);
    Route::post('usertypeOptionsForPermission', [PermissionController::class, 'getoptions']); 

    //hii


///////////////////////////////////////////////////////////////////////////////////////////////////////////    

});


// Route::get('/file-import', [ImportCustomerController::class, 'importView'])->name('import-view');//Controller doesn't exist




////////////////////////////////////////////////////////////////////////////////////////////////////





// Route::get('holidaytable',[HolidaysController::class,'HolidayMasterTable']);




/*
## Resource Laravel Routes Example
Route::post(['ulb',[UlbMasterController::class,'store']]);//
Route::get(['ulb/{id}',[UlbMasterController::class,'show']]);
Route::get(['ulb/edit/{id}',[UlbMasterController::class,'edit']]);//
Route::put/patch(['ulb/{id}',[UlbMasterController::class,'update']]);
## put=>If the record exists then update else create a new record
## Patch =>update/modify
Route::delete(['ulb/{id}',[UlbMasterController::class,'destroy']]);
*/

Route::resources([
    'ulb' => UlbMasterController::class,
    'state' => StateMasterController::class,
    'country' => CountryMasterController::class,
    'tendertype' => TenderTypeMasterController::class,
    'unit' => UnitMasterController::class,
    'tendercreation' => TenderCreationController::class,
    'city' => CityMasterController::class,
    'district' => DistrictMasterController::class,
    'customercreationmain' => CustomerCreationMainController::class,
    'customercreationprofile' => CustomerCreationProfileController::class,
    'customercreationcontact' => CustomerCreationContactPersonController::class,
    'customercreationsmwprojectstatus' => CustomerCreationSWMProjectStatusController::class,
    'competitorprofile' => CompetitorProfileCreationController::class,
    'competitorbranch' => CompetitorDetailsBranchesController::class,
    'competitorturnover' => CompetitorDetailsTurnOverController::class,
    'competitornetworth' => CompetitorDetailsCompanyNetWorthController::class,
    'competitorlineofbusiness' => CompetitorDetailsLineOfBusinessController::class,
    'competitorproscons' => CompetitorDetailsProsConsController::class,
    'competitorqcertificate' => CompetitorDetailsQualityCertificatesController::class,
    'competitorworkorder' => CompetitorDetailsWorkOrderController::class,
    'projecttype' => ProjectTypeController::class,
    'customersubcategory' => CustomerSubCategoryController::class,
    'projectstatus' => ProjectStatusController::class,
    'customercreationulbdetails' => ULBDetailsController::class,
    'customercreationbankdetails' => CustomerCreationBankDetailsController::class,
    'bidcreation/creation' => BidCreationCreationController::class,
    'bidcreation/creation/docupload' => BidCreationCreationDocsController::class,
    'tenderstatus' => BidManagementTenderStatusBiddersController::class,
    'workorder/creation/communicationfiles' => BidManagementWorkOrderCommunicationFilesController::class,
    'communicationfiles/docupload' => CommunicationDocController::class,
    'mobilization/creation' => BidManagementWorkOrderMobilizationAdvanceController::class,
    'ProjectDetails/Creation' => BidManagementWorkOrderProjectDetailsController::class,
    'workorder/creation/Workorder' => BidManagementWorkOrderWorkOrderController::class,
    'bidcreation/prebidqueries/docupload' => BidmanagementPreBidQueriesController::class,
    'bidcreation/corrigendumpublish/docupload' => BidmanagementCorrigendumPublishController::class,
    'bidcreation/tenderparticipation' => BidCreationTenderParticipationController::class,
    'bidcreation/bidsubmission/tenderfee' => BidCreationTenderFeeController::class,
    'bidcreation/bidsubmission/emdfee' => BidCreationEMDController::class,
    'bidcreation/bidsubmission/bidsubmittedstatus' => BidCreationBidSubmittedStatusController::class,
    'letteracceptance/creation' => BidManagementWorkOrderLetterOfAcceptenceController::class,
    'tenderstatus/techevaluation' => TenderStatusTechEvaluationController::class,
    'financialevaluation' => TenderStatusFinancialEvaluationsController::class,
    'bigmanagement/tenderstatus/status' => BidManagementTenderOrBidStausController::class, 
    'tenderstatusbidders' => TenderStatusBiddersController::class,
    'tenderstatus/awardcontract' => TenderStatusContractAwardedController::class,
    'attendanceTypeMaster' => AttendanceTypeMasterController::class,
    'attendanceentry' => AttendanceEntryController::class,
    'communicationfilesmaster' => CommunicationfilesmasterController::class,
    'usercreation' => UserControllerTemp::class,
    'calltype' => CallTypeController::class,
    'calltobdm' => CalltobdmController::class,
    'bizzforecast' => BusinessForecastController::class,
    'zonemaster' => ZoneMasterController::class,
    'expensetype' => ExpenseTypeController::class,
    'attendancetype'=> AttendanceTypeController::class,
    'callcreation' => CallCreationController::class,
    'callfileupload'=> CallLogFilesController::class,
    'callhistory'=> CallHistoryController::class,
    'otherexpense' => OtherExpensesController::class,
    'otherexpensesub' => OtherExpenseSubController::class,
    'attendanceregister'=>AttendanceRegisterController::class,
    'holidays'=>HolidaysController::class,
    
]);




//File uplaod Default location has been set by below line in config/filesystems.php file
//'root' => public_path()."/uploads",

//Can create a new folder inside public/uploads path
//$file->storeAs('competitor/qc', $fileName, 'public');  
