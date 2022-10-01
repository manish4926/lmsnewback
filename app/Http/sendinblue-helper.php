<?php

function connectEmail()
{
    $key = config('services.sendinblue')['client_key'];
    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $key);
    return $config;
}

function getAccount()
{
    $config = connectEmail();
    $apiInstance = new SendinBlue\Client\Api\AccountApi(
        new GuzzleHttp\Client(),
        $config
    );

    try {
    $result = $apiInstance->getAccount();
        print_r($result);
    } catch (Exception $e) {
        echo 'Exception when calling AccountApi->getAccount: ', $e->getMessage(), PHP_EOL;
    }
}


function sendSMTPMAIL($data)
{

    
    $config = connectEmail();
    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
        new GuzzleHttp\Client(),
        $config
    );
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail($data); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        //print_r($result);
        return 'Success';
    } catch (Exception $e) {
        echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        return 'Fail';
    }
}

function getSMTPTransactionalMailList()
{

    
    $config = connectEmail();
    
    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
        new GuzzleHttp\Client(),
        $config
    );
    $templateStatus = true; // bool | Filter on the status of the template. Active = true, inactive = false
    $limit = 50; // int | Number of documents returned per page
    $offset = 0; // int | Index of the first document in the page
    $sort = "desc"; // string | Sort the results in the ascending/descending order of record creation

    try {
        //$templateStatus, $limit, $offset, $sort
        $result = $apiInstance->getSmtpTemplates();
        //print_r($result);
        return $result;
    } catch (Exception $e) {
        echo 'Exception when calling TransactionalEmailsApi->getSmtpTemplates: ', $e->getMessage(), PHP_EOL;
    }
}