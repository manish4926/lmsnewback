<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\MyForm;
use App\Model\User;
use App\Model\Admissionform;


use Auth;
use DB;

class EmailAutomationController extends Controller
{
    public function automateEmailFlow(Request $request)
    {
    	$user = Auth::user();

    	$sources = MyForm::select('source')
    					->distinct()->get();

    	/*$callflow = DB::table('callflow')->get();

    	foreach ($sources as $key => $source) {
    		foreach ($callflow as $key => $flow) {
    			$finalsource = json_decode($flow->source)[0];
    			$finalsource = explode("-", $finalsource);
                unset($finalsource[0]);
                $finalsource = implode("-", $finalsource);
    			
    			if($finalsource == $source->source) {

    				$source->flowid = $flow->id;
    			}    			
    		}
    	}*/
    	$tablename = followuptablename(1);
    	$categories = followuptable(1)->select('category')
    					->distinct()->get();

        


    	/*$counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get();*/

        $emaillist = getSMTPTransactionalMailList();

        $messagelist = [
            'Welcome Message' => 'Welcome Message',
            'Application Form Message' => 'Application Form Message',
            'Introductory Message' => 'Introductory Message',
            'Scholarship Message' => 'Scholarship Message',
        ];
                    

    	return view('automation.email.flow',compact('request','user','sources','categories','emaillist', 'messagelist'));
    }

    public function automateEmailFlowSubmit(Request $request)
    {
        DB::table('mailautomateflow')->insert(
            ['commands' => $request->commands, 'source' => $request->sources, 'flowarray' => $request->sflowc]
        );
        return 'success';
    }

    public function automateEmailFlowGenerate(Request $request)
    {
        //DB::table('callflowchart')->truncate();

        $flowlist = DB::table('mailautomateflow')->get();
        foreach ($flowlist as $key => $flow) {
            $commands = json_decode($flow->commands);
            $flowsource = json_decode($flow->source)[0];
            $flowsource = explode("-", $flowsource);
            unset($flowsource[0]);
            $flowsource = implode("-", $flowsource);
            //dd($commands);
            foreach ($commands as $keycounsellor => $command) {
                //$counsellor_id = last(explode('-', $counsellor));
                $index_id = $command->id;
                $parent_id = $command->parentid;

                $content = explode('-',$command->content);
                $sendtype = last(explode('.', $content[0]));
                $template_id = last(explode('.', $content[1]));
                $delay_days = last(explode('.', $content[2]));
                $categorylist = last(explode('.', $content[3]));

                DB::table('emailflowchart')->insert(
                    ['source' => $flowsource, 'index_id' => $index_id, 'parent_id' => $parent_id, 'sendtype' => $sendtype, 'template_id' => $template_id, 'delay_days' => $delay_days, 'categorylist' => $categorylist]
                );
            }
            
        }
        return "success";
    }

    public function automateEmailHitMail(Request $request) {
        //$currentdate = '2021-01-30';
        $currentdate = date('Y-m-d');
        //dd($currentdate);
        $list = DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('hit_date','<=', $currentdate)->limit(100)->get();

                    

        foreach ($list as $key => $entry) {

            //Check Admission Forms
            $checkadmissionform = Admissionform::where('email',$entry->email)->count();
            if($checkadmissionform >= 1) {
                DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('email', $entry->email)->delete();
                continue;
            }
            

            $form = MyForm::where('email', $entry->email)->where('dupcheck', '=', 0)->orderBy('id', 'asc')->first();
            //dd($form->followcounts);

            if(empty($form->followcounts)) {
                DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('email', $entry->email)->delete();
                continue;
            }
            if($form->followcounts == 0) {
                DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('email', $entry->email)->delete();
                continue;
            }

            //dd($form);

            $lastremarks = followuptable($form->followcounts)->where('form_id', $form->id)->first();
            
            if($lastremarks->status == 0 AND $form->followcounts == 1) {
                DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('email', $entry->email)->delete();
                continue;   
            } elseif($lastremarks->status == 0) {
                $newfollowcount = $form->followcounts -1;
                $lastremarks = followuptable($newfollowcount)->where('form_id', $form->id)->first();
            }


            $flowlist = DB::table('emailflowchart')
                ->where('source', $entry->source)
                ->where('parent_id', $entry->parent_id)
                ->get();

            $junk_leads = array("Call Disconnected","Call Not Picked","Switch Off","Not Reachable","Junk","Wrong Number");
            $positive_leads = array("Interested for Admission","Need More Time","Form Filled","Will Check after Nov CAT","Will Check after Dec MAT","Will Check after Jan CMAT","Will Check after Feb MAT","Will Check after May MAT","Will Check after XAT", "Will Check after GMAT");
            $not_interested_leads = array("Callback","Concerned Person Not Available","Not Appeared In Any Entarance","Drop This Year","MBA Only","Looking For Another Course","Not Eligible","Not Interested");

            $categorylist = array('Junk Data List' => $junk_leads, 'Intersted Data List' => $positive_leads, 'Call Not Connected Data' => $not_interested_leads);

            //dd($flowlist);
            foreach ($flowlist as $key => $flow) {

                $categorymatch = arrayMatchKey($lastremarks->category,$categorylist);
                if(!empty($categorymatch) AND $flow->categorylist == $categorymatch) {
                    EmailAutomationController::sendToData($form->name, $form->email, $form->phone, $flow->sendtype, $flow->template_id);

                    $newparentid = $flow->index_id;
                    
                }
            }
            

            DB::table('emailautomationhitter')
                    ->where('type', 'Category')
                    ->where('email', $entry->email)->delete();

            //New Hit Date

            if(!empty($newparentid)) {
                $newhitdate = DB::table('emailflowchart')
                    ->where('source', $entry->source)
                    ->where('parent_id', $newparentid)
                    ->first();
            }

            if(!empty($newhitdate)) {
                $newdate = date('Y-m-d', strtotime($currentdate. ' + '.$newhitdate->delay_days.' days'));

                DB::table('emailautomationhitter')->insert([['source' => $entry->source,'email' => $entry->email, 'hit_date' => $newdate,'type' => 'Category','parent_id' => $newparentid]]);                
            }

            

        }

        return 'success';
    }

    public function sendToData($name, $email, $phone, $sendtype, $template_id)
    {

        if($sendtype == 'email') {
            $array = [
            'templateId' => (int)$template_id,
            'to' => [ 
                ['name' => $name,  'email'=> $email] 
            ], 
            'params' => [
                'FIRSTNAME' => $name,
                ] 
            ]; //Based on format selected in campaign

            $response = sendSMTPMAIL($array);

            /*if($request->ajax()){
                return $response;   
            }*/
        } 
        //echo $sendtype." - on ".$email;
        //dd('Mail Send');
    }

    

    //After Admission Form
    public function automateAfterFormFillEmailFlow(Request $request)
    {
        $user = Auth::user();

        
        $emaillist = getSMTPTransactionalMailList();

        $messagelist = [
            'Welcome Message' => 'Welcome Message',
            'Application Form Message' => 'Application Form Message',
            'Introductory Message' => 'Introductory Message',
            'Scholarship Message' => 'Scholarship Message',
        ];   

        return view('automation.email.afterformfillflow',compact('request','user','emaillist', 'messagelist'));
    }


    public function automateAfterFormFillEmailFlowSubmit(Request $request)
    {
        DB::table('emailafterformfillflowchart')->insert(
            ['commands' => $request->commands, 'source' => $request->sources, 'flowarray' => $request->sflowc]
        );
        return 'success';
    }

    public function automateAfterFormFillEmailFlowGenerate(Request $request)
    {
        DB::table('emailafterformfillflow')->truncate();

        $flowlist = DB::table('emailafterformfillflowchart')->get();
        foreach ($flowlist as $key => $flow) {
            //dd($flow);
            $commands = json_decode($flow->commands);
            $flowsource = json_decode($flow->source)[0];
            $flowsource = explode("-", $flowsource);
            unset($flowsource[0]);
            $flowsource = implode("-", $flowsource);
            //dd($commands);
            foreach ($commands as $keycounsellor => $command) {
                //$counsellor_id = last(explode('-', $counsellor));
                $index_id = $command->id;
                $parent_id = $command->parentid;

                $content = explode('-',$command->content);
                $sendtype = last(explode('.', $content[0]));
                $template_id = last(explode('.', $content[1]));
                $delay_days = last(explode('.', $content[2]));
                //$categorylist = last(explode('.', $content[3]));

                DB::table('emailafterformfillflow')->insert(
                    ['source' => $flowsource, 'index_id' => $index_id, 'parent_id' => $parent_id, 'sendtype' => $sendtype, 'template_id' => $template_id, 'delay_days' => $delay_days]
                );
            }
            
        }
        return "success";
    }

    public function automateAfterFormFillEmailHitMail(Request $request) {
        //$currentdate = '2021-03-28';
        $currentdate = date('Y-m-d');
        //dd($currentdate);
        $list = DB::table('emailautomationafterformfillhitter')
                    ->where('hit_date','<=', $currentdate)->limit(100)->get();

        foreach ($list as $key => $entry) {

            //Check Admission Forms
            $admissionform = Admissionform::where('email',$entry->email)->first();

            $flowlist = DB::table('emailafterformfillflow')
                ->where('source', $entry->source)
                ->where('parent_id', $entry->parent_id)
                ->get();

            foreach ($flowlist as $key => $flow) {

                    EmailAutomationController::sendToData($admissionform->name, $admissionform->email, $admissionform->phone, $flow->sendtype, $flow->template_id);

                    $newparentid = $flow->index_id;
                    
            }      
            

            DB::table('emailautomationafterformfillhitter')
                    ->where('email', $entry->email)->delete();

            //New Hit Date

            if(!empty($newparentid)) {
                $newhitdate = DB::table('emailafterformfillflow')
                    ->where('source', $entry->source)
                    ->where('parent_id', $newparentid)
                    ->first();
            }

            if(!empty($newhitdate)) {
                $newdate = date('Y-m-d', strtotime($currentdate. ' + '.$newhitdate->delay_days.' days'));

                DB::table('emailautomationafterformfillhitter')->insert([['source' => $entry->source,'email' => $entry->email, 'hit_date' => $newdate,'parent_id' => $newparentid]]);                
            }

            

        }

        return 'success';
    }
}
