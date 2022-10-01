<?php

Route::group(['prefix' => 'mail'], function () {

    Route::any('/send/hotleads/verification/email', ['as' => 'sendsmtphotleadverifyemail', 'uses' =>'EmailController@sendSmtpHotLeadVerifyEmail']); //We

});

Route::any('/form/verify/{token?}', ['as' => 'hotleadverify', 'uses' =>'ConversationController@hotLeadVerify']); //
Route::any('/form/welcome/page', ['as' => 'hotleadwelcome', 'uses' =>'ConversationController@hotLeadWelcome']); //
Route::any('/form/conversation/submit', ['as' => 'hotleadConversationSubmit', 'uses' =>'ConversationController@hotLeadConversationSubmit']); //