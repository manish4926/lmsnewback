<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'before' => 'auth.basic'], function () {
	Route::post('/forms/submit', 'ApiController@store');
	//Auth::guard('api')->user();
});

//Api Based Fetching
Route::group(['prefix' => 'api'], function () {

    Route::any('/inhouse/getimidiatedata', ['as' => 'getimidiatedata', 'uses' =>'ApiController@getImidiateData']);

    Route::any('/inhouse/getimidiate/appicationform', ['as' => 'getimidiateapplicationform', 'uses' =>'ApiController@getImidiateApplicationform']);

    Route::any('/inhouse/getwebsitehome', ['as' => 'getwebsitehome', 'uses' =>'ApiController@getWebsiteHome']);
    
    Route::any('/inhouse/getwebsiteinner', ['as' => 'getwebsiteinner', 'uses' =>'ApiController@getWebsiteInnerPage']);
    
    Route::any('/inhouse/getgoogle', ['as' => 'getgoogle', 'uses' =>'ApiController@getGoogleCampaign']);
    
    Route::any('/inhouse/getfacebook', ['as' => 'getfacebook', 'uses' =>'ApiController@getFacebookCampaign']);

    Route::any('/inhouse/getmbauniverse', ['as' => 'getmbauniverse', 'uses' =>'ApiController@getMBAUniverse']);

    Route::any('/inhouse/getcollegedunia/landing', ['as' => 'getcollegedunialanding', 'uses' =>'ApiController@getCollegeDuniaLandingPage']);

    Route::any('/inhouse/getipu', ['as' => 'getipu', 'uses' =>'ApiController@getIPU']);

    Route::any('/inhouse/getmocktest', ['as' => 'getmocktest', 'uses' =>'ApiController@getMockTest']);

    Route::any('/inhouse/getadmissionform', ['as' => 'getadmissionform', 'uses' =>'ApiController@getAdmissionForm']);

    Route::any('/shiksha/leads', ['as' => 'getshikshaleads', 'uses' =>'ApiController@getShikshaLeads']);

    Route::any('/shiksha/matched/leads', ['as' => 'getshikshamatchedleads', 'uses' =>'ApiController@getShikshaMatchedLeads']);

    Route::any('/career360/leads', ['as' => 'getcareersleads', 'uses' =>'ApiController@getCareersLeads']); //Career 360

    Route::any('/collegedekho/leads', ['as' => 'getcollegedekholeads', 'uses' =>'ApiController@getCollegeDekhoLeads']); //College Dekho

    Route::post('/collegedunai/leads', ['as' => 'getcollegedunaileads', 'uses' =>'ApiController@getCollegeDuniaLeads']); //College Dunia

    Route::post('/careerlauncher/leads', ['as' => 'getcareerlauncherleads', 'uses' =>'ApiController@getCareerLauncherLeads']); //Career Launcher

    Route::any('/rectifydate', ['as' => 'rectifydate', 'uses' =>'ApiController@rectifyDate']);

    Route::any('/inhouse/getlastinsertedid', ['as' => 'getlastinsertedid', 'uses' =>'ApiController@getLastInsertedId']);

    Route::any('/inhouse/verificationmailhitter', ['as' => 'verificationmailhitter', 'uses' =>'ApiController@verificationMailHitter']);

    Route::any('/shiksha/caf/forms', ['as' => 'getshikshacafforms', 'uses' =>'ApiController@getShikshaCafForms']);

    Route::any('/facebook/webhook/forms', ['as' => 'getfacebookwebhookforms', 'uses' =>'ApiController@getFacebookWebhookForms']);

    Route::any('/myoperator/webhook/oncall/forms', ['as' => 'getmyoperatoroncallforms', 'uses' =>'ApiController@getMyoperatorWebhookOncallForms']);

    Route::post('/kenyt/chat/leads', ['as' => 'getkenytchatleads', 'uses' =>'ApiController@getKenytChatLeads']); //Kenyt Chat Bot Leads

    Route::any('/getmyuni/leads', ['as' => 'getmyunileads', 'uses' =>'ApiController@getMyUniLeads']);


    
    /*Report Generator*/
    Route::any('/generate/formconversion', ['as' => 'generateformconversionreport', 'uses' =>'ReportGenerator@generateFormConversionReport']);

    Route::any('/generate/remarksreport', ['as' => 'generateremarksreportreport', 'uses' =>'ReportGenerator@generateRemarksReport']);

});


Route::group(['prefix' => 'zoho'], function () {
    Route::any('/generate/token', ['as' => 'generatetoken', 'uses' =>'ZohoController@generateToken']);
    Route::any('/refresh/token', ['as' => 'refreshtoken', 'uses' =>'ZohoController@refreshToken']);
});

Route::group(['prefix' => 'test'], function () {
    Route::any('/insert', ['as' => 'testinsert', 'uses' =>'ZohoController@testinsert']);
    Route::any('/view', ['as' => 'testview', 'uses' =>'ZohoController@testview']);
});