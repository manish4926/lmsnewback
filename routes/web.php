<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', ['as' => 'dashboard', 'uses' =>'MainController@dashboard']);
Route::get('/progress', ['as' => 'counsellorprogress', 'uses' =>'MainController@counsellorProgress']);
//Route::get('/', [MainController::class, 'dashboard']);

//----------------------AuthController----------------------------------------------
Route::get('/register', ['as' => 'register', 'uses' => 'AuthController@register']);	//Register Method

Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@login']);	//Register Method

Route::post('/registeruser', ['as' => 'registerMethod', 'uses' => 'AuthController@postRegister']);	//Register Method

Route::post('/loginuser', ['as' => 'loginMethod', 'uses' => 'AuthController@postSignIn']);	//Login Method

Route::get('/logout', [	'as' => 'logoutMethod', 'middleware' => 'auth', 'uses' => 'AuthController@getLogout']);	//LogOut Method

/*Route::get('/password/reset/{token?}', ['as' => 'forgotpasswordreset', 'uses' => 'Auth\PasswordController@showResetForm']);  //Show Reset Form (Forgot Password)

Route::post('/password/email', ['as' => 'forgotpassword', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);  //Forgot Password Method

Route::post('/password/reset', ['as' => 'passwordreset', 'uses' => 'Auth\PasswordController@reset']);  //Reset Forgotted Password*/

Route::get('/password/reset/{token?}', ['as' => 'forgotpasswordreset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);  //Show Reset Form (Forgot Password)

Route::post('/password/email', ['as' => 'forgotpassword', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);  //Forgot Password Method

Route::post('/password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset']);  //Reset Forgotted Password


Route::get('/facebookoauth', [ 'as' => 'facebooklogin', 
                        'uses' => 'AuthController@facebookLogin'
                        ]); //Facebook Login Page

Route::get('/facebookcallback', [ 'as' => 'facebookcallback', 
                        'uses' => 'AuthController@facebookCallBackUrl'
                        ]); //Facebook Redirect Url Page

Route::get('/googleoauth', [ 'as' => 'googlelogin', 
                        'uses' => 'AuthController@googleLogin'
                        ]); //Google Login page

Route::get('/googlecallback', [ 'as' => 'googlecallback', 
                        'uses' => 'AuthController@googleCallBackUrl'
                        ]); //Google Login Redirect

Route::get('/assignroles',              ['as' => 'assignroles',
                                            'uses' => 'AuthController@assignRoles',
                                            'roles' => ['Admin']
                                            ]);         //Admin Assign Roles

Route::post('/postadminassignroles',     ['as' => 'postadminassignroles',
                                            'uses' => 'AuthController@postAdminAssignRoles',
                                            'roles' => ['Admin']
                                            ]);         //Admin Assign Roles


Route::get('/profile/options', ['as' => 'profileoptions', 'uses' => 'AuthController@profileOptions']);  //Profile Option

Route::post('/profile/options/submit', ['as' => 'profileoptionssubmit', 'uses' => 'AuthController@profileOptionsSubmit']);  //Profile Option Submit


Route::group(['middleware' => 'auth'], function () 
{
//-------------------------Main Controller----------------------

Route::get('/addform', ['as' => 'addform', 'uses' =>'MainController@addForm']);

Route::post('/addformsubmit', ['as' => 'addformsubmit', 'uses' =>'MainController@addFormSubmit']);

Route::get('/addbulkform', ['as' => 'addbulkform', 'uses' =>'MainController@addBulkForm']);

Route::post('/addbulkformsubmit', ['as' => 'addbulkformsubmit', 'uses' =>'MainController@addBulkFormSubmit']);



Route::get('/addbulkresponse', ['as' => 'addbulkresponse', 'uses' =>'MainController@addBulkResponse']);

Route::post('/addbulkresponsesubmit', ['as' => 'addbulkresponsesubmit', 'uses' =>'MainController@addBulkResponseSubmit']);

Route::post('/addbulkresponsetwosubmit', ['as' => 'addbulkresponsetwosubmit', 'uses' =>'MainController@addBulkResponseTwoSubmit']); //View Can Be Used of Above Url - addbulkresponse

Route::get('/view/form', ['as' => 'viewforms', 'uses' =>'MainController@viewForm']);

Route::get('/view/form/detail', ['as' => 'getuserdetail', 'uses' =>'MainController@getUserDetail']);

Route::get('/source/data/{search?}', ['as' => 'sourcedata', 'uses' =>'MainController@sourceData']);

Route::any('/assign/data/{followupnum?}/', ['as' => 'assigndata', 'uses' =>'MainController@assignData'])->where(['followupnum' => '[0-9]+']);

Route::get('/assigned/data/{followupnum?}/', ['as' => 'assigneddata', 'uses' =>'MainController@assigneddata'])->where(['followupnum' => '[0-9]+']); //  Assigned Data To Counsellers 

Route::post('/assign/data/submit', ['as' => 'assigndatasubmit', 'uses' =>'MainController@assignDataSubmit']);

Route::post('/update/form/status/submit', ['as' => 'updateformstatussubmit', 'uses' =>'MainController@updateFormStatusSubmit']);

Route::post('/update/form/status/from/counsellorsubmit', ['as' => 'updateformstatusfromcounsellorsubmit', 'uses' =>'MainController@updateFormStatusFromCounsellorSubmit']); //Update Status from Counsellor List Page

Route::get('/followup/list/all/{followupnum?}', ['as' => 'followuplistall', 'uses' =>'MainController@followupListAll']);

Route::any('/followup/list/{followupnum?}', ['as' => 'followuplist', 'uses' =>'MainController@followupList']);

Route::get('/followup/view/{id}', ['as' => 'followupdetail', 'uses' =>'MainController@followupDetail']);

Route::post('/myoperator/call/direct/', ['as' => 'myoperatorcalldirect', 'uses' =>'MainController@myoperatorCallDirect']);

Route::post('/followup/submit', ['as' => 'followupsubmit', 'uses' =>'MainController@followupSubmit']);

Route::any('/followup/list/reminder/calls/', ['as' => 'followuplistrecall', 'uses' =>'MainController@followupListRecall']);

Route::any('/call/logs/{followupnum?}/', ['as' => 'calllogs', 'uses' =>'MainController@callLogs']);



Route::get('/analyse', ['as' => 'analyserecord', 'uses' =>'MainController@analyseRecord']);

Route::any('/counsellor/followup/{id}/{name?}/list/{followupnum}/', ['as' => 'counsellorfollowuplist', 'uses' =>'MainController@counsellorFollowupList']);    //Counsellor FollowupList

Route::post('/counsellor/transfer/calls/submit', ['as' => 'counsellortransfercallssubmit', 'uses' =>'MainController@counsellorTransferCallsSubmit']);

Route::post('/update/student/detail/submit', ['as' => 'updatestudentdetailsubmit', 'uses' =>'MainController@updateStudentDetailSubmit']);

Route::get('/admissionforms', ['as' => 'admissionform', 'uses' =>'MainController@admissionForm']);

Route::any('/check/admission/form', ['as' => 'checkadmissionform', 'uses' =>'MainController@checkAdmissionForm']);

Route::get('/admissions/list', ['as' => 'admissionslist', 'uses' =>'MainController@admissionsList']);

Route::get('/getadmissions/data', ['as' => 'getadmissionsdata', 'uses' =>'MainController@getadmissionsData']);

Route::post('/admissions/submit', ['as' => 'admissionssubmit', 'uses' =>'MainController@admissionsSubmit']);

Route::any('/closed/calls/{followupnum}/', ['as' => 'closedcalls', 'uses' =>'MainController@closedCalls']);

Route::post('/open/and/assign/calls/submit', ['as' => 'openandassigncallssubmit', 'uses' =>'MainController@openAndAssignCallsSubmit']);




/*Reports*/
Route::get('/download/excel/complete',  ['as' => 'downloadcompleteexcel', 'uses' => 'MainController@downloadCompleteExcel',
                                            'roles' => ['Admin']]);         //Admin Download Complete Excel

Route::get('/download/excel/unique',  ['as' => 'downloaduniqueexcel', 'uses' => 'MainController@downloadUniqueExcel',
                                            'roles' => ['Admin']]);         //Admin Download Unique Excel

Route::get('/download/excel/completewithremarks',  ['as' => 'downloadcompletewithremarksexcel', 'uses' => 'MainController@downloadCompleteWithRemarksExcel',
                                            'roles' => ['Admin']]);         //Admin Download Complete Excel



//-------------------------Report Controller----------------------
Route::any('/daily/counsellor/data',  ['as' => 'dailycounsellordata', 'uses' => 'ReportController@dailyCounsellorData']);         //Daily Counsellor Data

Route::any('/daily/assigned/report',  ['as' => 'dailyassigneddata', 'uses' => 'ReportController@dailyAssignedData', 'roles' => ['Admin']]);         //Daily Form Assigned Record

Route::any('/form/comparision/report',  ['as' => 'formcomparisionreport', 'uses' => 'ReportController@formComparisionReport', 'roles' => ['Admin']]);         //Form Comparision Report

Route::any('/daily/report',  ['as' => 'dailyreport', 'uses' => 'ReportController@dailyReport', 'roles' => ['Admin']]);         //Daily Report

Route::any('/gdpi/report',  ['as' => 'gdpireport', 'uses' => 'ReportController@gdpiReport', 'roles' => ['Admin']]);         //GDPI Report

Route::post('/update/gdpi/remarks',  ['as' => 'updatedgpiremarks', 'uses' => 'ReportController@updateGdpiRemarks', 'roles' => ['Admin']]);         //Update GDPI Report Remarks

Route::any('/form/conversion/report/excel',  ['as' => 'formConversionReportExcel', 'uses' => 'ReportController@formConversionReportExcel', 'roles' => ['Admin']]);         //Form Conversion Report

Route::any('/form/conversion/report',  ['as' => 'gdpiSourcereport', 'uses' => 'ReportController@gdpiSourceReport', 'roles' => ['Admin']]);         //Form Conversion Report

Route::any('/source/quality/report',  ['as' => 'sourcequalityreport', 'uses' => 'ReportController@sourceQualityReport', 'roles' => ['Admin']]);         //Source Unique Report

Route::any('/source/quality/report/2',  ['as' => 'sourcequalityreporttwo', 'uses' => 'ReportController@sourceQualityReportTwo', 'roles' => ['Admin']]);         //Source Unique Report 2

Route::any('/source/quality/report/3',  ['as' => 'sourcequalityreportthree', 'uses' => 'ReportController@sourceQualityReportThree', 'roles' => ['Admin']]);         //Source Quality Report 3


Route::any('/letter/dispatch/report',  ['as' => 'letterdispatchreport', 'uses' => 'ReportController@letterDispatchReport', 'roles' => ['Admin']]);       

Route::any('/gdpi/attendance',  ['as' => 'gdpiattendance', 'uses' => 'ReportController@gdpiAttendance', 'roles' => ['Admin']]);     

Route::post('/gdpi/attendance/submit',  ['as' => 'gdpiattendancesubmit', 'uses' => 'ReportController@gdpiAttendanceSubmit', 'roles' => ['Admin']]);       

Route::get('/source/admission/record', ['as' => 'sourceadmissionrecord', 'uses' =>'ReportController@sourceAdmissionRecord']);  


//Upload Report
Route::get('/upload/admitted/student', ['as' => 'uploadadmittedstudentlist', 'uses' =>'ReportController@uploadAdmittedStudentList']);

Route::post('/upload/admitted/student/submit', ['as' => 'uploadadmittedstudentlist', 'uses' =>'ReportController@uploadAdmittedStudentListSubmit']);


});




/*Others*/
Route::group(['prefix' => 'ajax'], function () {

    Route::post('/sms/submit', ['as' => 'sendsmssubmit', 'uses' =>'MainController@sendSmsSubmit']);

    Route::any('/check/email/exist', ['as' => 'checkemailexist', 'uses' =>'MainController@checkEmailExist']);

});




//SendinBlue Email Controller
Route::group(['prefix' => 'mail'], function () {

    Route::any('/home', ['as' => 'emailhome', 'uses' =>'EmailController@dashboard']);

    Route::any('/send/smtp/email', ['as' => 'sendsmtpemail', 'uses' =>'EmailController@sendSmtpEmail']);

    Route::any('/send/smtp/welcome/email', ['as' => 'sendsmtpwelcomeemail', 'uses' =>'EmailController@sendSmtpWelcomeEmail']); //We

});


/*Graph*/
Route::get('/counsellor/graph', ['as' => 'counsellorgraph', 'uses' =>'MainController@counsellorGraph']);


Route::any('/heatmap', ['as' => 'heatmap', 'uses' =>'CustomApiController@heatmap']);


/*Custom Controller*/
Route::get('/customcontroller/deletecustomfollowups', ['as' => 'deletecustomfollowups', 'uses' =>'CustomController@deleteCustomFollowups']);

Route::get('/customcontroller/updatecustomquery', ['as' => 'updatecustomquery', 'uses' =>'CustomController@updateCustomQuery']);





/*Chatbot*/

Route::group(['prefix' => 'chatbot'], function () {

    Route::get('/test', ['as' => 'chatbottest', 'uses' =>'ChatbotController@chatbotTest']);

    Route::post('/addcookies',    ['as' => 'addcookies',   'uses' => 'ChatbotController@addCookies']);

    Route::post('/response', ['as' => 'chatbotresponse', 'uses' =>'ChatbotController@chatbotResponse']);

    Route::post('/info/submit', ['as' => 'infoformsubmit', 'uses' =>'ChatbotController@infoFormSubmit']);

    Route::post('/child/response', ['as' => 'chatbotchildresponse', 'uses' =>'ChatbotController@chatbotChildResponse']);

    /*Admin*/

    Route::get('/dragbox', ['as' => 'dragbox', 'uses' =>'ChatbotController@dragbox']);


});


Route::any('/query/rectify/', ['as' => 'queryrectifyduplicate', 'uses' =>'MainController@queryrectifyduplicate']);
Route::any('/query/rectify/step2/{followupnum?}/', ['as' => 'queryrectifyduplicatesteptwo', 'uses' =>'MainController@queryrectifyduplicatesteptwo'])->where(['followupnum' => '[0-9]+']);

Route::any('/query/rectify/updatealldup', ['as' => 'queryrectifyupdatealldup', 'uses' =>'MainController@queryrectifyupdatealldup']);

require_once(__DIR__ . "/automate.php");
require_once(__DIR__ . "/hotleads.php");
