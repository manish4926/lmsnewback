<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\VerifyHotLead;
use App\Model\HotLead;
use App\Model\MyForm;

use Auth;
use DB;

class ConversationController extends Controller
{
    public function hotLeadVerify(Request $request)
    {

    	$formverify = VerifyHotLead::where('token',$request->token)->first();	

    	if(!empty($formverify)) {
    		$form_id = $formverify->form_id;
    		$form = MyForm::where('id',$form_id)->first();	
    		$password = mt_rand(100000, 999999);


    		$checkexist = HotLead::where('student_id', $form_id)->first();
            if(empty($checkexist)) {
                $hotleadid = HotLead::insertGetId(
                    ['student_id' => $form_id, 'name' => $form->name, 'course' => $form->course, 'email' => $form->email, 'password' => $password]
                );  
                
            } else {
                return "<h2>Your email is already verified.</h2>";
            }
    		

            VerifyHotLead::where('token',$request->token)->update(['status' => 1]); 

            //Also send mail and sms to user

			return redirect()->route('hotleadwelcome', ['id' => base64_encode($form_id)]);
    	}
    	else {
    		dd('Invalid Page');
    	}
    	dd($form);
    }

    public function hotLeadWelcome(Request $request)
    {
        $campaign_name = 'hotleads_welcome_login_mailer';
        $campaignId = getSendInBlueIdByName($campaign_name);

        $form_id = base64_decode($request->id);
        $form = MyForm::where('id',$form_id)->first(); 
        $logindetails = HotLead::where('student_id',$form_id)->first(); 

        //Send Welcome Mail to User
        $array = [
            'templateId' => $campaignId,
            'to' => [ 
                ['name' => $form->name,  'email'=> $form->email] 
            ], 
            'params' => [
                    'FIRSTNAME'  => $form->name,
                    'EMAIL'      => $form->email,
                    'PASSWORD'   => $logindetails->password
                ] 
            ]; //Based on format selected in campaign
            
        $response = sendSMTPMAIL($array);

        $message = "Dear Student, Thank you for your interest in JIMS Rohini Sector-5 \n Url: http://jimsinfo.org/microsite/studentzone/index.php \n Email: ".$form->email."\n Password: ".$logindetails->password;
        text_local_api($form->phone,$message);
        
    	return view('conversation.welcome',compact('request','form','logindetails'));
    }

    public function hotLeadConversationSubmit(Request $request)
    {
        $user = Auth::user();
        $form_id = $request->form_id;


        $lastconv = DB::table('hotleads_conversations')->where('form_id',$form_id)->orderBy('id', 'desc')->first(); 

        $counsellor_id = $user->id;
        if(!empty($lastconv)) {
            $ticket_key = $lastconv->ticket_key;
        } else {
            $ticket_key = random_strings(6);
        }
        
        $message = $request->message;

        DB::table('hotleads_conversations')->insert(
            ['form_id' => $form_id, 'counsellor_id' => $counsellor_id, 'message_by' => 'counsellor', 'message' =>$message, 'ticket_key' => $ticket_key, 'status' => 0]
        );
        
        return 'success';
    }
}
