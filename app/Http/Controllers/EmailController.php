<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

use App\Model\User;
use App\Model\Option;
use App\Model\MyForm;
use App\Model\WelcomeMailer;
use App\Model\VerifyHotLead;

use Auth;
use SendinBlue;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

use App\Http\Requests;

class EmailController extends Controller
{
    public function dashboard(Request $request)		//Dashboard
	{
		
		if (Auth::guest()) {
		    return redirect()->route('login');
		}
		$user = Auth::user();
        dd('Test Controller');
		
        
        //createSMTPTemp();
	}

	public function sendSmtpEmail(Request $request)
	{

		$campaignId = getSendInBlueIdByName($request->campaignid);
		$formid     = $request->formid;

		$form = MyForm::where('id', '=', $formid)
						->first();

		
		$array = [
			'templateId' => $campaignId,
		 	'to' => [ 
		 		['name' => $form->name,  'email'=> $form->email] 
		 	], 
		 	'params' => [
		 		'FIRSTNAME' => $form->name,
		 		] 
		 	]; //Based on format selected in campaign

		$response = sendSMTPMAIL($array);

		if($request->ajax()){
			return $response;	
        }
		dd($response);
	}

	public function sendSmtpWelcomeEmail(Request $request)
	{
		$campaign_name = 'interested_lead_welcome_mailer';
		$campaignId = getSendInBlueIdByName($campaign_name);
		$current_timestamp = date('Y-m-d, h:i:s');
		$selecteddate = date('Y-m-d', strtotime("-5 day"));

		$list = WelcomeMailer::where('status', 0)->orderBy('id', 'asc')->where(DB::raw('Date(created_at)'),$selecteddate)->get();

		

		$newlist = array();
		$namelist = array();
		foreach ($list as $key => $value) {
			$array = [
			'templateId' => $campaignId,
		 	'to' => [ 
		 		['name' => $value->name,  'email'=> $value->email] 
		 	], 
		 	'params' => [
		 		'FIRSTNAME' => $value->name,
		 		] 
		 	]; //Based on format selected in campaign
		 	$response = sendSMTPMAIL($array);
		 	
		}

		//Delete Once Mail Send
		
		WelcomeMailer::where('status', 0)->where(DB::raw('Date(created_at)'),$selecteddate)->update(['status' => 1, 'mailed_at' => $current_timestamp]);
		//dd($response);
		return "Success";
	}

	public function sendSmtpHotLeadVerifyEmail(Request $request)
    {
    	$campaign_name = 'hotleads_welcome_mailer';
		$campaignId = getSendInBlueIdByName($campaign_name);

		$leads_mail_sender_list = DB::table('leads_mail_sender_list')->select('form_id','email')->limit(50)->orderBy('id', 'asc')->get();   	

    	foreach ($leads_mail_sender_list as $key => $lead) {
    		$random_id = mt_rand(100000, 999999);
    		$token = Str::random(40);
    		

    		$check = VerifyHotLead::where('email',$lead->email)->first();
    		if(empty($check)):
	    		VerifyHotLead::insert(
				    ['form_id' => $lead->form_id, 'token' => $token]
				);

				$verfication_link = route('hotleadverify',$token);
				$form = MyForm::select('name','email')->where('id', $lead->form_id)->first();		

				//$form->email = 'manish.arora@jimsindia.org';
	    		$array = [
				'templateId' => $campaignId,
			 	'to' => [ 
			 		['name' => $form->name,  'email'=> $form->email] 
			 	], 
			 	'params' => [
						'FIRSTNAME'  => $form->name,
						'EMAIL'      => $form->email,
						'RAND_ID'    => $random_id,
						'VERIFYLINK' => $verfication_link
			 		] 
			 	]; //Based on format selected in campaign
			 	
			 	$response = sendSMTPMAIL($array);
			 	
    		else:
    			//check if already verified
    			if($check->status == 1) {
    				continue;
    			}
    			$token = $check->token;
    		endif;
    		

    		
		 	
		 	//dd($array);
		 	
    	}
    	//Delete first items
    	DB::table('leads_mail_sender_list')->limit(50)->orderBy('id', 'asc')->delete();
    	return "success";
    }
}