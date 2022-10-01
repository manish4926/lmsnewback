<?php

Route::group(['prefix' => 'queryassignment'], function () {
    
    Route::get('/automateflow', ['as' => 'automateqeryflow', 'uses' =>'QueryAssignmentController@automateQueryFlow']);
    Route::post('/automateflowsubmit', ['as' => 'automateqeryflowsubmit', 'uses' =>'QueryAssignmentController@automateQueryFlowSubmit']);

    Route::post('/import/automateflowsubmit', ['as' => 'importautomateqeryflowsubmit', 'uses' =>'QueryAssignmentController@importAutomateQueryFlowSubmit']);


    Route::post('/automate/callflow/generate', ['as' => 'automatecallflowgenerate', 'uses' =>'QueryAssignmentController@automateCallFlowGenerate']);

    Route::get('/allot/calls', ['as' => 'allotcalls', 'uses' =>'QueryAssignmentController@allotCalls']);
});

Route::group(['prefix' => 'automation'], function () {

    Route::group(['prefix' => 'email'], function () {

    	Route::get('/flow', ['as' => 'automateemailflow', 'uses' =>'EmailAutomationController@automateEmailFlow']);
    	Route::post('/flow/submit', ['as' => 'automateemailflowsubmit', 'uses' =>'EmailAutomationController@automateEmailFlowSubmit']);

        Route::post('/flow/import', ['as' => 'automateemailflowimport', 'uses' =>'EmailAutomationController@automateEmailFlowImport']);

    	Route::get('/flow/generate', ['as' => 'automateemailflowgenerate', 'uses' =>'EmailAutomationController@automateEmailFlowGenerate']);

    	Route::get('/hit/mail', ['as' => 'automateemailhitmail', 'uses' =>'EmailAutomationController@automateEmailHitMail']);

    });
    
    //After Filling Admission Form
    Route::group(['prefix' => 'afterformfill/email'], function () {

        Route::get('/flow', ['as' => 'afterformfillautomateemailflow', 'uses' =>'EmailAutomationController@automateAfterFormFillEmailFlow']);

        Route::post('/flow/submit', ['as' => 'afterformfillautomateemailflowsubmit', 'uses' =>'EmailAutomationController@automateAfterFormFillEmailFlowSubmit']);

        Route::post('/flow/import', ['as' => 'afteradmissionautomateemailflowimport', 'uses' =>'EmailAutomationController@automateAfterFormFillEmailFlowImport']);

        Route::get('/flow/generate', ['as' => 'afteradmissionautomateemailflowgenerate', 'uses' =>'EmailAutomationController@automateAfterFormFillEmailFlowGenerate']);

        Route::get('/hit/mail', ['as' => 'afteradmissionautomateemailhitmail', 'uses' =>'EmailAutomationController@automateAfterFormFillEmailHitMail']);
    });

    Route::group(['prefix' => 'report'], function () {

        Route::get('/source/quality/report', ['as' => 'automateemailsourcequalityreportmail', 'uses' =>'ReportController@automateSourceQualityReportMail']);

        Route::get('/daily/counsellor/report', ['as' => 'automatedailycounsellorreportmail', 'uses' =>'ReportController@automateDailyCounsellorReportMail']);

        Route::get('/daily/counsellor/quality/report', ['as' => 'automatedailycounsellorqualityreportmail', 'uses' =>'ReportController@automateDailyCounsellorQualityReportMail']);
    });
    
});