<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

use App\Model\User;
use App\Model\Role;
use App\Model\MyForm;
use App\Model\Followup;
use App\Model\Admissionform;
use App\Model\Sms;
use App\Model\Option;
use App\Model\GdpiDetail;
use App\Model\Gdpiattendance;
use App\Model\Admission;
use App\Model\Formconversioreport;

use Auth;
use Session;
use Excel;
use View;

use Carbon\Carbon;
use App\Http\Requests;

use App\Exports\FormConversionReportExport;

class ReportController extends Controller
{

	public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $profile = !empty($user) ? $user->getProfile() : '';
            $this->userProfile = $profile;
            View::share(['userProfile' => $this->userProfile ]);
            return $next($request);
        });
    }


    public function dailyCounsellorData(Request $request)
	{
		$user = Auth::user();
		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d');
			$enddate = date('Y-m-d');
		}



		$allfollowupbydate = array();
        $allfollowupbydateMAT = array();

		if($user->hasRole('Admin') == true) {
			
			$counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
					->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get();
            //dd($counsellors);
            
            foreach ($counsellors as $counsellor) {
            	$totalfollowup = 0; 
				$opencalls = 0;
	            $closedcalls = 0;
	            $completedcalls = 0;

				for ($i=1; $i <= 5; $i++) { 
		        	$tablename = followuptablename($i);

		        	$count = followuptable($i)
		        		->where("$tablename.status",1)
		        		->where("counsellor_id",$counsellor->id)
	                    ->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
	                    ->where('message_type' ,'=', '')
	                    ->where('reentry' ,'=', 0)
	                    ->count();

	                $varname = 'followup'.$i;
	                $$varname = $count;
	                $totalfollowup += $count; 
	                

	                $followupStatusCount = followuptable($i)
	                	->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
	                	->join('forms', 'forms.id', '=', "$tablename.form_id")
	                	->where("counsellor_id",$counsellor->id)
	                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
	                    ->where("$tablename.status",1)
	                    ->where('message_type' ,'=', '')
	                    ->where('reentry' ,'=', 0)
	                    ->groupBy('forms.status')
	                    ->get()->keyBy('status');

	                
	                $opencalls = $opencalls + (!empty($followupStatusCount['open']) ? $followupStatusCount['open']->counter : 0);
	                $closedcalls = $closedcalls + (!empty($followupStatusCount['closed']) ? $followupStatusCount['closed']->counter : 0);
	                $opencalls = $opencalls + (!empty($followupStatusCount['complete']) ? $followupStatusCount['complete']->counter : 0);

		        }
		        
	        	
				$followup = array('name' => $counsellor->firstname, 'startdate' => $startdate, 'enddate' => $enddate, 'total' => $totalfollowup,'open' => $opencalls,'closed' => $closedcalls,'completed' => $completedcalls, 'first' => $followup1, 'second' => $followup2, 'third' => $followup3, 'fourth' => $followup4, 'fifth' => $followup5);

				//Pass it to main/base array
            	array_push($allfollowupbydate, $followup);


            	//For MAT DATA
				$totalfollowup = 0; 
				$opencalls = 0;
	            $closedcalls = 0;
	            $completedcalls = 0;

				for ($i=1; $i <= 5; $i++) { 
		        	$tablename = followuptablename($i);

		        	$count = followuptable($i)
		        		->join('forms', 'forms.id', '=', "$tablename.form_id")
		        		->where('forms.source' ,'like', '%MAT%')
		        		->where("$tablename.status",1)
		        		->where("counsellor_id",$counsellor->id)
	                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
	                    ->where('message_type' ,'=', '')
	                    ->where('reentry' ,'=', 0)
	                    ->count();

	                $varname = 'followup'.$i;
	                $$varname = $count;
	                $totalfollowup += $count; 
	                

	                $followupStatusCount = followuptable($i)
	                	->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
	                	->join('forms', 'forms.id', '=', "$tablename.form_id")
	                	->where("counsellor_id",$counsellor->id)
	                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
	                    ->where('forms.source' ,'like', '%MAT%')
	                    ->where("$tablename.status",1)
	                    ->where('message_type' ,'=', '')
	                    ->where('reentry' ,'=', 0)
	                    ->groupBy('forms.status')
	                    ->get()->keyBy('status');

	                
	                $opencalls = $opencalls + (!empty($followupStatusCount['open']) ? $followupStatusCount['open']->counter : 0);
	                $closedcalls = $closedcalls + (!empty($followupStatusCount['closed']) ? $followupStatusCount['closed']->counter : 0);
	                $opencalls = $opencalls + (!empty($followupStatusCount['complete']) ? $followupStatusCount['complete']->counter : 0);

		        }
		        
				$followup = array('name' => $user->firstname, 'startdate' => $startdate, 'enddate' => $enddate, 'total' => $totalfollowup,'open' => $opencalls,'closed' => $closedcalls,'completed' => $completedcalls, 'first' => $followup1, 'second' => $followup2, 'third' => $followup3, 'fourth' => $followup4, 'fifth' => $followup5);
				//Pass it to main/base array
            	array_push($allfollowupbydateMAT, $followup);
            	
            }		
			
		}  

		$counsellorfollowupbydate = array();
		$counsellorfollowupbydateMAT = array();
		if($user->hasRole('Counsellor') == true OR ($user->hasRole('FrontDesk') == true)) {
			$user = Auth::user();
			
			$totalfollowup = 0; 
			$opencalls = 0;
            $closedcalls = 0;
            $completedcalls = 0;

			for ($i=1; $i <= 5; $i++) { 
	        	$tablename = followuptablename($i);

	        	$count = followuptable($i)
	        		->where("$tablename.status",1)
	        		->where("counsellor_id",$user->id)
                    ->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->count();

                $varname = 'followup'.$i;
                $$varname = $count;
                $totalfollowup += $count; 
                

                $followupStatusCount = followuptable($i)
                	->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
                	->join('forms', 'forms.id', '=', "$tablename.form_id")
                	->where("counsellor_id",$user->id)
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->where("$tablename.status",1)
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->groupBy('forms.status')
                    ->get()->keyBy('status');

                
                $opencalls = $opencalls + (!empty($followupStatusCount['open']) ? $followupStatusCount['open']->counter : 0);
                $closedcalls = $closedcalls + (!empty($followupStatusCount['closed']) ? $followupStatusCount['closed']->counter : 0);
                $opencalls = $opencalls + (!empty($followupStatusCount['complete']) ? $followupStatusCount['complete']->counter : 0);

	        }
	        
        	
			$followup = array('name' => $user->firstname, 'startdate' => $startdate, 'enddate' => $enddate, 'total' => $totalfollowup,'open' => $opencalls,'closed' => $closedcalls,'completed' => $completedcalls, 'first' => $followup1, 'second' => $followup2, 'third' => $followup3, 'fourth' => $followup4, 'fifth' => $followup5);

			array_push($counsellorfollowupbydate, $followup);

			//For MAT DATA
			
			$totalfollowup = 0; 
			$opencalls = 0;
            $closedcalls = 0;
            $completedcalls = 0;

			for ($i=1; $i <= 5; $i++) { 
	        	$tablename = followuptablename($i);

	        	$count = followuptable($i)
	        		->join('forms', 'forms.id', '=', "$tablename.form_id")
	        		->where('forms.source' ,'like', '%MAT%')
	        		->where("$tablename.status",1)
	        		->where("counsellor_id",$user->id)
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->count();

                $varname = 'followup'.$i;
                $$varname = $count;
                $totalfollowup += $count; 
                

                $followupStatusCount = followuptable($i)
                	->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
                	->join('forms', 'forms.id', '=', "$tablename.form_id")
                	->where("counsellor_id",$user->id)
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->where('forms.source' ,'like', '%MAT%')
                    ->where("$tablename.status",1)
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->groupBy('forms.status')
                    ->get()->keyBy('status');

                
                $opencalls = $opencalls + (!empty($followupStatusCount['open']) ? $followupStatusCount['open']->counter : 0);
                $closedcalls = $closedcalls + (!empty($followupStatusCount['closed']) ? $followupStatusCount['closed']->counter : 0);
                $opencalls = $opencalls + (!empty($followupStatusCount['complete']) ? $followupStatusCount['complete']->counter : 0);

	        }
	        
			$followup = array('name' => $user->firstname, 'startdate' => $startdate, 'enddate' => $enddate, 'total' => $totalfollowup,'open' => $opencalls,'closed' => $closedcalls,'completed' => $completedcalls, 'first' => $followup1, 'second' => $followup2, 'third' => $followup3, 'fourth' => $followup4, 'fifth' => $followup5);
			array_push($counsellorfollowupbydateMAT, $followup);
		}


		return view('dailycounsellorreport',compact('request','user','allfollowupbydate', 'allfollowupbydateMAT','counsellorfollowupbydate', 'counsellorfollowupbydateMAT', 'startdate', 'enddate'));
	}


	public function dailyAssignedData(Request $request)
	{
		$user = Auth::user();
		$counsellor_id = $request->counsellor_id;
		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d');
			$enddate = date('Y-m-d');
		}


		$alldates = getAllDatesbetweenTwoDates($startdate, $enddate);
		$basearray = array();
		foreach ($alldates as $date) {

			$counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
					->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get();

            $allassignedbydate = array();
            foreach ($counsellors as $counsellor) {
            	if(!empty($counsellor_id ) AND ($counsellor_id != $counsellor->id)) {
            		continue;
            	}

            	$tablename = followuptablename(1);
            	$reportfirst = followuptable(1)->select(DB::raw('*,count(forms.source) as counter'))
								->join('forms', 'forms.id', '=', "$tablename.form_id")
								->where(DB::raw("Date($tablename.created_at)") ,'=', $date)
								->whereNotIn('course', ['BBA','BCA', 'MCA'])
								//->where('level', '=', 0)
								->where('reentry', '=', 0)
								->where('counsellor_id', '=', $counsellor->id)
								->groupBy('forms.source')
								->get();

				$firstfollowupsource = array();
				$firstcallssum = 0;
				foreach ($reportfirst as $value) {
					array_push($firstfollowupsource, $value->source);
					$firstcallssum += $value->counter;
				}

				$tablename = followuptablename(2);
				$reportsecond = followuptable(2)->select(DB::raw('*,count(forms.source) as counter'))
								->join('forms', 'forms.id', '=', "$tablename.form_id")
								->where(DB::raw("Date($tablename.created_at)") ,'=', $date)
								->whereNotIn('course', ['BBA','BCA', 'MCA'])
								//->where('level', '=', 1)
								->where('reentry', '=', 0)
								->where('counsellor_id', '=', $counsellor->id)
								->groupBy('forms.source')
								->get();

				$secondfollowupsource = array();
				$secondcallssum = 0;
				foreach ($reportsecond as $value) {
					array_push($secondfollowupsource, $value->source);
					$secondcallssum += $value->counter;
				}

				$tablename = followuptablename(3);
				$reportthird = followuptable(3)->select(DB::raw('*,count(forms.source) as counter'))
								->join('forms', 'forms.id', '=', "$tablename.form_id")
								->where(DB::raw("Date($tablename.created_at)") ,'=', $date)
								->whereNotIn('course', ['BBA','BCA', 'MCA'])
								//->where('level', '=', 2)
								->where('reentry', '=', 0)
								->where('counsellor_id', '=', $counsellor->id)
								->groupBy('forms.source')
								->get();

				$thirdfollowupsource = array();
				$thirdcallssum = 0;
				foreach ($reportthird as $value) {
					array_push($thirdfollowupsource, $value->source);
					$thirdcallssum += $value->counter;
				}

				$tablename = followuptablename(4);
				$reportfourth = followuptable(4)->select(DB::raw('*,count(forms.source) as counter'))
								->join('forms', 'forms.id', '=', "$tablename.form_id")
								->where(DB::raw("Date($tablename.created_at)") ,'=', $date)
								->whereNotIn('course', ['BBA','BCA', 'MCA'])
								//->where('level', '=', 3)
								->where('reentry', '=', 0)
								->where('counsellor_id', '=', $counsellor->id)
								->groupBy('forms.source')
								->get();

				$fourthfollowupsource = array();
				$fourthcallssum = 0;
				foreach ($reportfourth as $value) {
					array_push($fourthfollowupsource, $value->source);
					$fourthcallssum += $value->counter;
				}

				$tablename = followuptablename(5);
				$reportfifth = followuptable(5)->select(DB::raw('*,count(forms.source) as counter'))
								->join('forms', 'forms.id', '=', "$tablename.form_id")
								->where(DB::raw("Date($tablename.created_at)") ,'=', $date)
								->whereNotIn('course', ['BBA','BCA', 'MCA'])
								//->where('level', '=', 4)
								->where('reentry', '=', 0)
								->where('counsellor_id', '=', $counsellor->id)
								->groupBy('forms.source')
								->get();

				$fifthfollowupsource = array();
				$fifthcallssum = 0;
				foreach ($reportfifth as $value) {
					array_push($fifthfollowupsource, $value->source);
					$fifthcallssum += $value->counter;
				}

				/*if($counsellor->id == '10') {
					dd($fifthcallssum);
				}*/

				$total = $firstcallssum + $secondcallssum + $thirdcallssum + $fourthcallssum + $fifthcallssum;
				
				$arr = array('date' => $date, 'total' => $total, 'name' => $counsellor->firstname." ".$counsellor->lastname, 'course' => 'PGDM', 'firstcallassigned' => $firstcallssum, 'firstsource' => $firstfollowupsource, 'secondcallassigned' => $secondcallssum, 'secondsource' => $secondfollowupsource, 'thirdcallassigned' => $thirdcallssum, 'thirdsource' => $thirdfollowupsource, 'fourthcallassigned' => $fourthcallssum, 'fourthsource' => $fourthfollowupsource, 'fifthcallassigned' => $fifthcallssum, 'fifthsource' => $fifthfollowupsource);

				array_push($basearray, $arr);	
            }
		}
		$followupassigneddata = $basearray;

		
		return view('admin.dailyassignedreport',compact('request','user', 'followupassigneddata', 'startdate', 'enddate', 'counsellors', 'counsellor_id'));
	}


	public function formComparisionReport(Request $request) {

		$user = Auth::user();
		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d');
			$enddate = date('Y-m-d');
		}
		$currentyeardata = array();

		
		$admissionforms = Admissionform::select('payment_mode', DB::raw('count(email) as total'))
							->Where('form_status', '=', 1)
							->whereBetween(DB::raw('Date(created_at)') ,[$startdate, $enddate])
							->groupBy('payment_mode')
							->get();

		$formsale = array('year' => '2018', 'Cash' => 0, 'DD' => 0, 'Online' => 0, 'total' => 0);
		$total = 0;
		foreach ($admissionforms as $admissionform) {
			$formsale[$admissionform->payment_mode] = $admissionform->total;
			$total += $admissionform->total;
		}
		$formsale['total'] = $total;

		$currentyeardata['formsale'] = $formsale;

		/**
		 * Enquiry Status
		 * Call defined as home page enquiry + Google + Facebook + helpline + landline not from shiksha, carieer 360 or mbauniverse
		 * Email defined as inner page enquiry
		 * Walkin refer to walkin
		 * Chat refer to chat only
		 */
		$calls     = MyForm::whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
                                        ->whereIn('source', ['Website Home Page', 'Google', 'Facebook', 'Landline', 'Helpline'])
                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
                                        ->count(); 


        $walkin    = MyForm::whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
                                        ->whereIn('source', ['Walk In'])
                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
                                        ->count(); 

        $email     = MyForm::whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
                                        ->whereIn('source', ['Website Inner Page'])
                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
                                        ->count(); 

        $chats     = MyForm::whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
                                        ->whereIn('source', ['Chat'])
                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
                                        ->count(); 

        $total = $calls + $walkin + $email + $chats;
		$enquirystatus = array('calls' => $calls, 'walkin' => $walkin, 'email' => $email, 'chats' => $chats, 'total' => $total);
		
		$currentyeardata['enquirystatus'] = $enquirystatus;
					

		return view('admin.formconversionreport',compact('request','user','currentyeardata'));	
	}

	public function dailyReport(Request $request)
	{
		$user = Auth::user();
		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d');
			$enddate = date('Y-m-d');
		}

		$alldates = getAllDatesbetweenTwoDates($startdate, $enddate);
		$basearray = array();
		foreach ($alldates as $date) {

			$calls     = MyForm::where(DB::raw('Date(posted_at)'), $date)
	                                        ->whereIn('source', ['Website Home Page', 'Landline', 'Helpline'])
	                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
	                                        ->count(); 

	        $totalwalkin    = MyForm::where(DB::raw('Date(posted_at)'), $date)
	                                        ->whereIn('source', ['Walk In'])
	                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
	                                        ->count(); 

	        $email     = MyForm::where(DB::raw('Date(posted_at)'), $date)
	                                        ->whereIn('source', ['Website Inner Page'])
	                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
	                                        ->count(); 

	        $total_queries = $calls + $totalwalkin + $email;

	        $cashforms = Admissionform::where(DB::raw('Date(date)'), $date)
	        								->where('payment_mode', 'Cash')
	        								->where('programme_af', 'NOT LIKE', '%PT%')
	        								->where('programme_af', '!=', '---Fellowship Programme in Management-I')
	        								->count();

	        $ddreceived = Admissionform::where(DB::raw('Date(date)'), $date)
	        								->where('payment_mode', 'DD')
	        								->where('programme_af', 'NOT LIKE', '%PT%')
	        								->where('programme_af', '!=', '---Fellowship Programme in Management-I')
	        								->count();

	        $total_form_sale = $cashforms + $ddreceived;	//Offline

	        $online_forms = Admissionform::where(DB::raw('Date(date)'), $date)
	        								->where('payment_mode', 'Online')
	        								->where('programme_af', 'NOT LIKE', '%PT%')
	        								->where('programme_af', '!=', '---Fellowship Programme in Management-I')
	        								->count(); //Non Part Time

	        $online_parttime_forms = Admissionform::where(DB::raw('Date(date)'), $date)
	        								->where('programme_af', 'LIKE', '%PT%')
	        								->count(); //Part Time for Cash and Online

	        $total_form_received = $online_forms + $online_parttime_forms;

	        $arr = array('date' => $date, 'calls' => $calls, 'totalwalkin' => $totalwalkin, 'email' => $email, 'total_queries' => $total_queries, 'cashforms' => $cashforms, 'ddreceived' => $ddreceived, 'total_form_sale' => $total_form_sale, 'online_forms' => $online_forms, 'online_parttime_forms' => $online_parttime_forms, 'total_form_received' => $total_form_received);


			array_push($basearray, $arr);	
    	}
    	$dailyreportdata = $basearray;

    	//dd(array_sum(array_column($dailyreportdata,'calls')));
		return view('admin.dailyreport',compact('request','user', 'dailyreportdata'));	
	}


	public function gdpiReport(Request $request)
	{
		$user = Auth::user();
		$gdpi_date = $request->date;

		$gdpilist = Admissionform::leftjoin('gdpidetails', 'gdpidetails.gdpidate', '=', 'admissionforms.gd_pi_date')
						->get();

		$array = array();
		$i = 0;

		foreach($gdpilist as $list){
			//$i++;
			$arraykey = !empty($list->gd_pi_date) ? $list->gd_pi_date."-c-".$list->gd_pi_center : '';
			if (array_key_exists($list->gd_pi_date , $array))
			{
				/*if($list->date < $list->gd_pi_date) {
					$array[$list->gd_pi_date]['applicants'] = $array[$list->gd_pi_date]['applicants'] + 1;
				}*/
				
				if(empty($list->gd_pi_date)) {
					$array[$arraykey]['applicants'] = $array[$arraykey]['applicants'] + 1;
				} elseif($list->date < $list->gd_pi_date) {
					$array[$arraykey]['applicants'] = $array[$arraykey]['applicants'] + 1;
				} else {
					//Ignore it
				}
			}
			else
			{
				if(empty($list->gd_pi_date)) {
					$array[$arraykey]['applicants'] = 1;
				} elseif($list->date < $list->gd_pi_date) {
					$array[$arraykey]['applicants'] = 1;
				} else {
					$array[$arraykey]['applicants'] = 0;
				}
				
				if($list->gd_pi_date == null) {
					$array[$arraykey]['gd_pi_center'] = 'Not Decided';
				} else {
					$array[$arraykey]['gd_pi_center'] = $list->gd_pi_center;
				}
				$array[$arraykey]['gd_pi_date'] = $list->gd_pi_date;
				$array[$arraykey]['extraforms'] = $list->extraforms;
				$array[$arraykey]['remarks'] = $list->remarks;
				
			}
			/*if($list->gdpi_result != '00') {
				if(!empty($array[$arraykey]['appeared'])) {
					$array[$arraykey]['appeared'] = $array[$arraykey]['appeared'] + 1;	
				} else {
					$array[$arraykey]['appeared'] = 1;
				}
			}*/

			if(($list->date >= $list->gd_pi_date) AND !empty($list->gd_pi_date)) {
				if(!empty($array[$arraykey]['form_sold'])) {
					$array[$arraykey]['form_sold'] = $array[$arraykey]['form_sold'] + 1; 
				} else {
					$array[$arraykey]['form_sold'] = 1;
				}
			} else {
				$array[$arraykey]['form_sold'] = 0;
			}
		}
//dd($array);
		$gdpiappeared = Gdpiattendance::groupBy('gd_pi_date', 'gd_pi_center')
						->get();

		foreach ($array as $key => $value) {

			if(empty($array[$key]['appeared'])) {
				$array[$key]['appeared'] = 0;
			}

			if(empty($array[$key]['form_sold'])) {
				$array[$key]['form_sold'] = 0;
			}

			$array[$key]['applicants']    = $array[$key]['applicants'] - $array[$key]['extraforms']; //Update applications with extraforms
			$array[$key]['percentage']    = perc_calc($array[$key]['appeared'], $array[$key]['applicants']);
			$array[$key]['absent']        = $array[$key]['applicants'] - $array[$key]['appeared'];
			$array[$key]['totalappeared'] = $array[$key]['appeared'] + $array[$key]['form_sold'] + $array[$key]['extraforms'];
		}

		$gdpireport = $array;

		return view('admin.gdpireport',compact('request','user', 'gdpireport'));	
	}


	public function updateGdpiRemarks(Request $request)
	{
		$user = Auth::user();
		//dd($request->gdpidate);
		$gdpiremarks = GdpiDetail::where('gdpidate', '=', $request->gdpidate)
                    		->first();

		if(empty($gdpiremarks)) {
            $gdpiremarks = new GdpiDetail;
            $gdpiremarks->gdpidate 		= $request->gdpidate;
            $gdpiremarks->extraforms    = $request->extraforms;
            $gdpiremarks->remarks       = $request->remarks;
            $gdpiremarks->save();
        
        } else {
            GdpiDetail::where('id', $gdpiremarks->id)
            	->update(['extraforms' => $request->extraforms, 'remarks' => $request->remarks]);
        }
        Session::flash('successMessage', 'Data Successfully Updated');
        return redirect()->back();

	}

	public function gdpiSourceReport(Request $request)
	{
		$user = Auth::user();

		/*if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d',strtotime("-7 day"));
			$enddate = date('Y-m-d');
		}

		$admissionforms = Formconversioreport:: 
							whereBetween(DB::raw('Date(date)'), [$startdate, $enddate])
							->get();*/

		$admissionforms = Admissionform::where('programme_af' ,'!=', '---Fellowship Programme in Management-I')->get(); //

		return view('admin.gdpisourcereport',compact('request','user','admissionforms'));	
	}	

	public function formConversionReportExcel(Request $request)
	{
		$mytime     = Carbon::now();
        return Excel::download(new FormConversionReportExport, 'form-conversion-report-excel-'.$mytime.'.csv');
	}


	public function uploadAdmittedStudentList(Request $request)		//Upload Student Admissions
	{
		$user = Auth::user();
    	return view('admin.uploadadmittedstudents',compact('request','user'));
	}

	public function uploadAdmittedStudentListSubmit(Request $request)		//Upload Student Admissions Submit
	{
		$user = Auth::user();
    	$file = $request->file('file');
        $form_match_status = 1;
        
        // Insert Products Excel File and Upload to Database
        $fileformat = $file->getClientOriginalExtension();
        if(($fileformat == 'xls') or ($fileformat == 'xlsx'))
        {
            /*$excel = Excel::load($file, function($reader) {
            })->get();*/
            $excel = Excel::selectSheetsByIndex('0')->load($file, function($reader) {
            })->get();
            $data = array();
            //dd($excel[0]->items);
            //dd($excel);

            //firstly check entry exist with form id
            foreach ($excel as $row) {
            	$admissionforms = Admissionform::where('regid', $row->reg_no)->count();
            	if($admissionforms == 0) {
            		$form_match_status = 0;
            		return "Id Mismatch";
            	}
            }


            foreach ($excel as $row) {
            	$admission_date = date('Y-m-d h:i:s',strtotime($row->admission_date));
            	$gdpi_date = date('Y-m-d h:i:s',strtotime($row->gdpi_date));


                /*$myform = new MyForm;
                $myform->name      = $row->name;
                $myform->email     = $row->email;
                $myform->phone     = $row->phone;
                $myform->city      = $row->city;
                $myform->course    = $row->course;
                $myform->query     = $row->message;
                $myform->source    = $row->source;
                $myform->status    = 'open';
                $myform->posted_at = date('Y-m-d 00:00:00', strtotime($row->date));
                $myform->save();*/
            }

        }
            
        
        return "Success";
	}


	public function sourceQualityReport(Request $request)
	{
		$user = Auth::user();	

		/**
		 * This Data Does not Contain 
		 	'BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme']
		 */

		$arr = array(
	        'Not Interested' => 0,
	        'Switch Off' => 0,
	        'Not Reachable' => 0,
	        'Concerned Person Not Available' => 0,
	        'Call Back Later' => 0,
	        'Call Disconnected' => 0,
	        'Wrong Number' => 0,
	        'Request to Call' => 0,
	        'Call Not Picked' => 0,
	        'Others' => 0,
	        'total' => 0,
	    );


		$myforms = MyForm::select('source','email')
            ->whereNotIn('course', ['BBA','BCA','MCA','Fellowship Programme in Management','FPM','FPM programme'])
            ->where('source','!=', 'Chatbot')
            ->groupBy('email')
            ->orderBy('id', 'asc')
            ->get();
        $array      = array();
        

        foreach($myforms as $myform)
        {
            if (array_key_exists($myform->source,$array))
			{
				$array[$myform->source]['unique'] = $array[$myform->source]['unique'] + 1;

			}
			else
			{
				
				$array[$myform->source]['unique'] = 1;
			}
			
            
        }

        //$completeSourceData = getSourceDataforGraph();
        $completeSourceData    = MyForm::select('source', DB::raw('count(id) as total'))
			                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
			                        ->where('source','!=', 'Chatbot')
			                        ->groupBy('source')
			                        ->get()->keyBy('source')->toArray();

        foreach ($completeSourceData as $key => $value) {
        	if(empty($array[$key] )) {
        		continue;
        	}
        	$array[$key]['total'] = $value['total'];
        	$array[$key]['remarks'] = $arr;
        }

        /*Remarks*/
        $sourcedata    = Followup::select('source','category','comment')
                        	->where('followups.level', '=', 0)
							->where('followups.reentry', '=', 0)
							->where('followups.status', '=', 1)
							->where('followups.message_type', '=', '')
							->where('forms.source', '!=', '')
							->where('forms.source', '!=', 'Chatbot')
                        	->join('forms', 'forms.id', '=', 'followups.form_id')
                        	->groupBy('followups.form_id')
                        	->get()->toArray();
//dd($sourcedata);
        foreach ($sourcedata as $source) {
  //      	dd($source);
        	$array[$source['source']]['remarks']['total'] = $array[$source['source']]['remarks']['total'] + 1;
        	

	        if (strpos(strtolower($source['comment']), strtolower('not interested')) !== false) {
	            $array[$source['source']]['remarks']['Not Interested'] = $array[$source['source']]['remarks']['Not Interested'] + 1;
	        } 
	        elseif(strpos(strtolower($source['comment']), strtolower('Not Reachable')) !== false) {
	            $array[$source['source']]['remarks']['Switch Off'] = $array[$source['source']]['remarks']['Switch Off'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Not Reachable')) !== false) {
	            $array[$source['source']]['remarks']['Not Reachable'] = $array[$source['source']]['remarks']['Not Reachable'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Concerned Person Not Available')) !== false) {
	            $array[$source['source']]['remarks']['Concerned Person Not Available'] = $array[$source['source']]['remarks']['Concerned Person Not Available'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Call Back Later')) !== false) {
	            $array[$source['source']]['remarks']['Call Back Later'] = $array[$source['source']]['remarks']['Call Back Later'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Call Disconnected')) !== false) {
	            $array[$source['source']]['remarks']['Call Disconnected'] = $array[$source['source']]['remarks']['Call Disconnected'] + 1;
	        }
	        elseif(strpos($source['comment'], 'Wrong Number') !== false) {
	            $array[$source['source']]['remarks']['Wrong Number'] = $array[$source['source']]['remarks']['Wrong Number'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Wrong Number')) !== false) {
	            $array[$source['source']]['remarks']['Request to Call'] = $array[$source['source']]['remarks']['Request to Call'] + 1;
	        }
	        elseif(strpos(strtolower($source['comment']), strtolower('Request to Call')) !== false) {
	            $array[$source['source']]['remarks']['Call Not Picked'] = $array[$source['source']]['remarks']['Call Not Picked'] + 1;
	        }
	        else {
	            $array[$source['source']]['remarks']['Others'] = $array[$source['source']]['remarks']['Others'] + 1;
	        }

	    }
	    //dd($array);
        $sourcedata = $array;

        return view('admin.sourcequalityreport',compact('request','user','sourcedata'));
	}


	public function sourceQualityReportTwo(Request $request)
	{
		$user = Auth::user();	

		/**
		 * This Data Does not Contain 
		 	'BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme']
		 */
		$search = $request->search;

		$sources = MyForm::select('source')
                    ->distinct()
                    ->get();

		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d',strtotime("-7 day"));
			$enddate = date('Y-m-d');
		}

		$array      = array();

		$arr = array(
			'Interested for Admission' => 0,
			'Need More Time' => 0,
			'Form Filled' => 0,
			'Will Check after Nov CAT' => 0,
			'Will Check after Dec MAT' => 0,
			'Will Check after Jan CMAT' => 0,
			'Will Check after Feb MAT' => 0,
			'Will Check after May MAT' => 0,
			'Will Check after XAT' => 0,
			'Will Check after GMAT'=> 0,
	        /*'Interested' => 0,*/
	        'Callback' => 0,
	        'Looking For Another Course' => 0,
	        'Concerned Person Not Available' => 0,
	        'Call Not Picked' => 0,
	        'Call Disconnected' => 0,
	        'Not Reachable' => 0,
	        'Switch Off' => 0,
	        'Not Interested' => 0,
	        'Wrong Number' => 0,
	        'Not Eligible' => 0,
	        'Junk' => 0,
	        'MBA Only' => 0,
	        'Drop This Year' => 0,
	        'Not Appeared In Any Entarance' => 0,
	        'Admission Taken in Another College' => 0,
	        'Send to Whatsapp' => 0,
	        'Golden Calls' => 0,
	        'Alloted' => 0,
	        'Total' => 0,
	    );

	    $completeSourceData    = MyForm::select('source', DB::raw('count(id) as total'))
			                        //->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
			                        ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
			                        ->when($search, function ($myform) use ($search) {
					                    if($search != 'All') {
					                        return $myform->where('source', $search);    
					                    }
					                    
					                })
			                        ->groupBy('source')
			                        ->get()->keyBy('source')->toArray();

		//Query Defined By Rajkamal Sir
		$sourceUniqueData    = MyForm::select('source', DB::raw('count(DISTINCT(email)) as total'))
			                        //->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
			                        ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
			                        ->when($search, function ($myform) use ($search) {
					                    if($search != 'All') {
					                        return $myform->where('source', $search);    
					                    }
					                    
					                })
			                        ->groupBy('source')
			                        ->get()->keyBy('source')->toArray();


        foreach ($completeSourceData as $key => $value) {
        	
        	$array[strtolower($key)]['total'] = $value['total'];
        	$array[strtolower($key)]['remarks'] = $arr;
        }

        foreach ($sourceUniqueData as $key => $value) {
        	$array[strtolower($key)]['uniqueinsourse'] = $value['total'];
        }


	    /*Remarks*/
        $firstfollowups    = followuptable(1)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

                        	//dd($firstfollowups);

        $secondfollowups   = followuptable(2)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $thirdfollowups    = followuptable(3)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $fourthfollowups    = followuptable(4)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $fifthfollowups    = followuptable(5)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();


		$myforms = MyForm::select('id','source','email')
            //->whereNotIn('course', ['BBA','BCA','MCA','Fellowship Programme in Management','FPM','FPM programme'])
            ->where('dupcheck', '=', 0)
            ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
            ->when($search, function ($myform) use ($search) {
                if($search != 'All') {
                    return $myform->where('source', $search);    
                }
                
            })
            //->groupBy('email')
            ->orderBy('id', 'asc')
            ->get();

        foreach($myforms as $myform)
        {
        	/*if($myform->source == 'Dec MAT Opted') {
        		dd($array);
        		dd($myform->source);
        	}*/
            if (array_key_exists('unique',$array[strtolower($myform->source)]))
			{
				$array[strtolower($myform->source)]['unique'] = $array[strtolower($myform->source)]['unique'] + 1;
			}
			else
			{
				$array[strtolower($myform->source)]['unique'] = 1;
			}
            
			/*$category = (
			 (!empty($fifthfollowups[$myform['id']])) ? $fifthfollowups[$myform['id']]['category'] :
			  ((!empty($fourthfollowups[$myform['id']])) ? $fourthfollowups[$myform['id']]['category'] :
			   ((!empty($thirdfollowups[$myform['id']])) ? $thirdfollowups[$myform['id']]['category'] :
			   	((!empty($secondfollowups[$myform['id']])) ? $secondfollowups[$myform['id']]['category'] :
			     ((!empty($firstfollowups[$myform['id']])) ? $firstfollowups[$myform['id']]['category'] : "Alloted"))))
			);*/
			$category = "";
			if(!empty($fifthfollowups[$myform['id']])) {
				$category = $fifthfollowups[$myform['id']]->category;
			} elseif((!empty($fourthfollowups[$myform['id']]))) {
				$category = $fourthfollowups[$myform['id']]->category;
			} elseif((!empty($thirdfollowups[$myform['id']]))) {
				$category = $thirdfollowups[$myform['id']]->category;
			} elseif((!empty($secondfollowups[$myform['id']]))) {
				$category = $secondfollowups[$myform['id']]->category;
			} elseif((!empty($firstfollowups[$myform['id']]))) {
				$category = $firstfollowups[$myform['id']]->category;
			}


			
			//$category = ReportController::sourceQualityReportPushArray($arr, $category);
			//dd($array);
			if(!empty($category)) {
				// if(empty($array[$myform->source]['remarks'][$category])) {
				// 	//dd($myform->source);
				// 	dd($category);
				// }
				$array[strtolower($myform->source)]['remarks'][$category]++;
				$array[strtolower($myform->source)]['remarks']['Total']++;
			}
        }

        $newarr =array();
        foreach($array as $key => $val) {
        	//$newarr
        	foreach($val['remarks'] as $k => $v)
        	{
        		$newarr[$k][$key] = $v;
        		//dd($k);
        	}
        	$newarr['No. Of Leads on LMS '][$key] = $val['total'];
        	$newarr['Unique In Source '][$key] = $val['uniqueinsourse'];
        	if(empty($val['unique'])) {
        		$val['unique'] = 0;
        	}
        	$newarr['Unique Leads'][$key] = $val['unique'];
        }

        $sourcedata = $newarr;

        return view('admin.sourcequalityreporttwo',compact('request','user','sourcedata','arr','sources'));
	}

	private function sourceQualityReportPushArray($arr, $value) 
	{
		//This method is created to be used in above function only
		if (array_key_exists($value,$arr) == false)
		{
			if($value == 'Interested for Admission' OR $value == 'Need More Time' OR $value == 'Form Filled' OR $value == 'Will Check after Nov CAT' OR $value == 'Will Check after Dec MAT' OR $value == 'Will Check after Jan CMAT' OR $value == 'Will Check after Feb MAT' OR $value == 'Will Check after May MAT' OR $value == 'Will Check after XAT' OR $value == 'Will Check after GMAT') {
				$value = 'Interested';
			}
		}
		return $value;
	}


	public function sourceQualityReportThree(Request $request)
	{
		$user = Auth::user();	

		$search = $request->search;

		$sources = MyForm::select('source')
                    ->distinct()
                    ->get();
        

		if(!empty($request->startdate)) {
			$startdate = date('Y-m-d',strtotime($request->startdate));
			$enddate   = date('Y-m-d',strtotime($request->enddate));

		} else {
			$startdate = date('Y-m-d',strtotime("-7 day"));
			$enddate = date('Y-m-d');
		}

		$myforms = MyForm::where('dupcheck', 0)
			->whereNotIn('course', ['BBA','BCA','MCA','Fellowship Programme in Management','FPM','FPM programme'])
			->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
			->when($search, function ($myform) use ($search) {
                    if($search != 'All') {
                        return $myform->where('source', $search);    
                    }
                    
                })
            ->groupBy('email')
            ->orderBy('id', 'asc')
            ->get()->keyBy('id')->toArray();

        

        $formIdList = array();
        $followupCategoryCount = array();

        foreach ($myforms as $key => $value) {
        	//var_dump($value['id']);
        	array_push($formIdList , $value['id']);
        }

        $totalleads = count($myforms);

        $newarr = array();
        $trees = ReportController::testme(0, $formIdList, $newarr);

        return view('admin.sourcequalityreportthree',compact('request','user','totalleads','formIdList', 'trees', 'sources'));

	}

	public function testme($followupnum, $formIdList, $newarr) {

		$junk_leads = array("Call Disconnected","Call Not Picked","Switch Off","Not Reachable","Junk","Wrong Number");
        $positive_leads = array("Interested for Admission","Need More Time","Form Filled","Will Check after Nov CAT","Will Check after Dec MAT","Will Check after Jan CMAT","Will Check after Feb MAT","Will Check after May MAT","Will Check after XAT");
        $not_interested_leads = array("Callback","Concerned Person Not Available","Not Appeared In Any Entarance","Drop This Year","MBA Only","Looking For Another Course","Not Eligible","Not Interested");

        $totalleads = count($formIdList);


		$sourcedata    = followuptable($followupnum+1)->select('id','form_id','level', 'category')
    					->whereIn('form_id', $formIdList)
						//->where('level', '=', $followupnum)
						->where('reentry', '=', 0)
						->where('status', '=', 1)
						->where('message_type', '=', '')
                    	->get()->toArray();

        $followupCategoryCount = array();

 
        $junk_leads_arr = $positive_leads_arr = $not_interested_leads_arr = array();
		foreach ($sourcedata as $key => $value) {
        	$category = $value->category;
        	
        	if (in_array($category, $junk_leads)) {
        		
        		if(empty($followupCategoryCount['junk_leads'])) {
        			$followupCategoryCount['junk_leads'] = 0;
        		}
        		$followupCategoryCount['junk_leads']++;
        		array_push($junk_leads_arr, $value->form_id);
        	}
        	elseif(in_array($category, $positive_leads)) {
        		if(empty($followupCategoryCount['positive_leads'])) {
        			$followupCategoryCount['positive_leads'] = 0;
        		}
        		$followupCategoryCount['positive_leads']++;
        		array_push($positive_leads_arr, $value->form_id);
        	}
        	elseif(in_array($category, $not_interested_leads)) {
        		if(empty($followupCategoryCount['not_interested_leads'])) {
        			$followupCategoryCount['not_interested_leads'] = 0;
        		}
        		$followupCategoryCount['not_interested_leads']++;
        		array_push($not_interested_leads_arr, $value->form_id);
        	}

        }


        $n = array();

        foreach ($followupCategoryCount as $key => $value) {

        	if($value != 0) {

        		$newlevel = $followupnum + 1;
        		$newlist = ${$key."_arr"};
        		
        		$r = ReportController::testme($newlevel, $newlist, $newarr);
        		
        		$r['count'] = count($newlist);

        		
        		
        		$n[$followupnum][$key] = $r;
        		
        	}
        	
        }

        $totalfollowup = array_sum($followupCategoryCount);
        $notcalled = $totalleads - $totalfollowup;
        $n[$followupnum]['not_called']['count'] = $notcalled;

        $newarr = $n;

        /*foreach ($followupCategoryCount as $key => $value) {

        	if($value != 0) {

        		$newlevel = $followupnum + 1;
        		$newlist = ${$key."_arr"};

        		//dd($followupnum);
        		
        		$r = ReportController::testme($newlevel, $newlist, $newarr);
        		
        		
        		$r['count'] = count($newlist);

        		
        		
        		$newarr[$followupnum][$key] = $r;
        		
        	}
        	
        }*/
        
        
        return $newarr;
	}


	public function letterDispatchReport(Request $request)
	{
		$user = Auth::user();

		$gdpilist = Admissionform::where('gdpi_result', '!=', '00')
						->leftjoin('admissions', 'admissions.regid', '=', 'admissionforms.regid')
						->get();

		$array = array();
		$i = 0;

		foreach($gdpilist as $list){
			
		}
//if($list->gdpi_result != '00') {
		dd($gdpilist);
		return view('admin.letterdispatchreport',compact('request','user','gdpilist'));	
	}

	public function gdpiAttendance(Request $request)
	{
		$user = Auth::user();	
		$gdpidates = Admissionform::select('gd_pi_date')
						->distinct()
						->where('gd_pi_date', '!=', '')
						->get();	

		$gdpilocations = Admissionform::select('gd_pi_center')
							->distinct()
							->where('gd_pi_center', '!=', '')
							->get();	

		$studentlist = Admissionform::where('admissionforms.gd_pi_date', '!=', '')
							->where('admissionforms.gd_pi_center', '!=', '')
							->whereNotIn('regid',function($query) {
							   $query->select('regid')->from('gdpiattendance');
							});
							
							
		if($request->gd_pi_date AND $request->gd_pi_date != 'All'){
			$studentlist->where('gd_pi_date', '=', $request->gd_pi_date);
		}
		if($request->gd_pi_center AND $request->gd_pi_center != 'All'){
			$studentlist->where('gd_pi_center', '=', $request->gd_pi_center);
		}
		$studentlist = $studentlist->get();
//dd($request);
//dd($studentlist);
		return view('admin.gdpiattendance',compact('request','user','gdpidates', 'gdpilocations','studentlist'));	
	}

	public function gdpiAttendanceSubmit(Request $request)
	{
		$studentlist = json_decode($request->studentlist);
		$gd_pi_date   = $request->gd_pi_date;
		$gd_pi_center = $request->gd_pi_center;
		$current_time = Carbon::now();
        
        $data = array();
        foreach($studentlist as $list) {
        	
            array_push($data,array('regid'=> $list, 'attendance'=> 1, 'gd_pi_date'=> $gd_pi_date, 'gd_pi_center'=> $gd_pi_center, 'created_at' => $current_time, 'updated_at' => $current_time));
        }
        Gdpiattendance::insert($data);
        Session::flash('successMessage', 'Attendance Successfully Done');
        return redirect()->back();
	}

	public function sourceAdmissionRecord(Request $request)
	{
		$user = Auth::user();

		$sources = MyForm::select('source')
					->whereNotNull('source')
                    ->distinct()
                    ->get()->keyBy('source')->toArray();

        $completeSourceData    = MyForm::select('source', DB::raw('count(id) as total'))
			                        ->whereNotNull('source')
			                        ->groupBy('source')
			                        ->orderBy('id', 'asc')
			                        ->get()->keyBy('source')->toArray();
		
		$uniqueSourceData    = MyForm::select('source', DB::raw('count(DISTINCT(email)) as total'))
			                        ->where('dupcheck',0)
			                        ->whereNotNull('source')
			                        ->groupBy('source')
			                        ->orderBy('id', 'asc')
			                        ->get()->keyBy('source')->toArray();


		$admissionlist = Admissionform::select(DB::raw('forms.id as formid, admissionforms.email as pemail'))
								->whereNotNull('formid')
								->where('programme_af' ,'!=', '---Fellowship Programme in Management-I')
								->leftjoin('forms', 'forms.email', '=', 'admissionforms.email')
								->orderBy('forms.posted_at', 'desc')
								//->groupBy('admissionforms.email')
								->get()->keyBy('pemail')->toArray();

		$admissionformlist = array();
		foreach ($admissionlist as $key => $value) {
			array_push($admissionformlist, $value['formid']);
		}
		unset($admissionlist);

		//dd($admissionformlist);
		$formfilled = MyForm::select('source','id', DB::raw('count(id) as total'))
			->whereIn('id', $admissionformlist)
			->orderBy('id', 'asc')	    
			->groupBy('source')
		    ->get()->keyBy('source')->toArray();

		unset($admissionformlist);


		$gdpilist = Gdpiattendance::select(DB::raw('forms.id as formid'))
							->leftjoin('admissionforms', 'gdpiattendance.regid', '=', 'admissionforms.regid')
							->leftjoin('forms', 'forms.email', '=', 'admissionforms.email')
							->groupBy('admissionforms.email')
							->get()->keyBy('formid')->toArray();


		$gdpigiven = MyForm::select('source', DB::raw('count(id) as total'))
			->whereIn('id', function($query) use ($gdpilist)
			    {
			        $query->select('id')
			              ->from('forms')
			              ->whereIn('id', $gdpilist)
			              ->groupBy('email')
			              ->orderBy('id', 'asc');
			    })
			->orderBy('id', 'asc')	    
			->groupBy('source')
		    ->get()->keyBy('source')->toArray();

		unset($gdpilist);



		$admissionlist = Admission::select(DB::raw('forms.id as formid'))
							->leftjoin('admissionforms', 'admissions.regid', '=', 'admissionforms.regid')
							->leftjoin('forms', 'forms.email', '=', 'admissionforms.email')
							->groupBy('admissionforms.email')
							->get()->keyBy('formid')->toArray();


		$admissiontaken = MyForm::select('source', DB::raw('count(id) as total'))
			->whereIn('id', function($query) use ($admissionlist)
			    {
			        $query->select('id')
			              ->from('forms')
			              ->whereIn('id', $admissionlist)
			              ->groupBy('email')
			              ->orderBy('id', 'asc');
			    })
			->orderBy('id', 'asc')	    
			->groupBy('source')
		    ->get()->keyBy('source')->toArray();

		unset($admissionlist);

		
        $arr = array();
        foreach ($sources as $key => $value) {

        	$arr[$value['source']] = array('total' => 0,'unique' => 0,'formfilled' => 0,'gdpigiven' => 0,'admissiontaken' => 0);
        	//dd($completeSourceData[$value['source']]['total']);
        	$arr[$value['source']]['total'] = $completeSourceData[$value['source']]['total'];

        	if(!empty($uniqueSourceData[$value['source']])) {
        		$arr[$value['source']]['unique'] = $uniqueSourceData[$value['source']]['total'];
        	}

        	if(!empty($formfilled[$value['source']])) {
        		$arr[$value['source']]['formfilled'] = $formfilled[$value['source']]['total'];	
        	}

        	if(!empty($gdpigiven[$value['source']])) {
        		$arr[$value['source']]['gdpigiven'] = $gdpigiven[$value['source']]['total'];	
        	}
        	
        	if(!empty($admissiontaken[$value['source']])) {
        		$arr[$value['source']]['admissiontaken'] = $admissiontaken[$value['source']]['total'];	
        	}
        	
        }	

        /*For Admission Forms not found in DATA*/
        $admissionformNotFoundlist = Admissionform::select('regid')
								->whereNull('forms.id')
								->where('programme_af' ,'!=', '---Fellowship Programme in Management-I')
								->leftjoin('forms', 'forms.email', '=', 'admissionforms.email')
								->groupBy('admissionforms.email')
								->get()->toArray();

		$admissionforms_NA  = count($admissionformNotFoundlist);

		$gdpi_NA = Gdpiattendance::whereIn('regid', $admissionformNotFoundlist)
						->count();

		$admission_NA = Admission::whereIn('regid', $admissionformNotFoundlist)
						->count();

		$arr['Form Not Found'] = array('total' => 0,'unique' => 0,'formfilled' => $admissionforms_NA,'gdpigiven' => $gdpi_NA,'admissiontaken' => $admission_NA);


        $sourcedata = $arr;
        return view('admin.sourceadmissionrecord',compact('request','user','sourcedata'));
	}

	public function automateSourceQualityReportMail(Request $request)
	{
		/**
		 * This Data Does not Contain 
		 	'BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme']
		 */

		$sources = MyForm::select('source')
                    ->distinct()
                    ->get();

		$startdate = date('Y-m-d',strtotime("-7 day"));
		$enddate = date('Y-m-d');

		$array      = array();

		$arr = array(
			'Interested for Admission' => 0,
			'Need More Time' => 0,
			'Form Filled' => 0,
			'Will Check after Nov CAT' => 0,
			'Will Check after Dec MAT' => 0,
			'Will Check after Jan CMAT' => 0,
			'Will Check after Feb MAT' => 0,
			'Will Check after May MAT' => 0,
			'Will Check after XAT' => 0,
			'Will Check after GMAT'=> 0,
	        /*'Interested' => 0,*/
	        'Callback' => 0,
	        'Looking For Another Course' => 0,
	        'Concerned Person Not Available' => 0,
	        'Call Not Picked' => 0,
	        'Call Disconnected' => 0,
	        'Not Reachable' => 0,
	        'Switch Off' => 0,
	        'Not Interested' => 0,
	        'Wrong Number' => 0,
	        'Not Eligible' => 0,
	        'Junk' => 0,
	        'MBA Only' => 0,
	        'Drop This Year' => 0,
	        'Not Appeared In Any Entarance' => 0,
	        'Admission Taken in Another College' => 0,
	        'Send to Whatsapp' => 0,
	        'Golden Calls' => 0,
	        'Alloted' => 0,
	        'Total' => 0,
	    );

	    $completeSourceData    = MyForm::select('source', DB::raw('count(id) as total'))
			                        //->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
			                        ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
			                        ->groupBy('source')
			                        ->get()->keyBy('source')->toArray();

		//Query Defined By Rajkamal Sir
		$sourceUniqueData    = MyForm::select('source', DB::raw('count(DISTINCT(email)) as total'))
			                        //->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
			                        ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
			                        ->groupBy('source')
			                        ->get()->keyBy('source')->toArray();


        foreach ($completeSourceData as $key => $value) {
        	
        	$array[$key]['total'] = $value['total'];
        	$array[$key]['remarks'] = $arr;
        }

        foreach ($sourceUniqueData as $key => $value) {
        	$array[$key]['uniqueinsourse'] = $value['total'];
        }


	    /*Remarks*/
        $firstfollowups    = followuptable(1)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $secondfollowups   = followuptable(2)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $thirdfollowups    = followuptable(3)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $fourthfollowups    = followuptable(4)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();

        $fifthfollowups    = followuptable(5)->select('id','form_id','level','category')
							->where('reentry', '=', 0)
							->where('status', '=', 1)
							->where('message_type', '=', '')
                        	->get()->keyBy('form_id')->toArray();


		$myforms = MyForm::select('id','source','email')
            //->whereNotIn('course', ['BBA','BCA','MCA','Fellowship Programme in Management','FPM','FPM programme'])
            ->where('dupcheck', '=', 0)
            ->whereBetween(DB::raw('Date(posted_at)'), [$startdate, $enddate])
            //->groupBy('email')
            ->orderBy('id', 'asc')
            ->get();

        //dd($array);
        foreach($myforms as $myform)
        {
            if (array_key_exists('unique',$array[$myform->source]))
			{
				$array[$myform->source]['unique'] = $array[$myform->source]['unique'] + 1;
			}
			else
			{
				$array[$myform->source]['unique'] = 1;
			}
            
			if(!empty($fifthfollowups[$myform['id']])) {
				$category = $fifthfollowups[$myform['id']]->category;
			} elseif((!empty($fourthfollowups[$myform['id']]))) {
				$category = $fourthfollowups[$myform['id']]->category;
			} elseif((!empty($thirdfollowups[$myform['id']]))) {
				$category = $thirdfollowups[$myform['id']]->category;
			} elseif((!empty($secondfollowups[$myform['id']]))) {
				$category = $secondfollowups[$myform['id']]->category;
			} elseif((!empty($firstfollowups[$myform['id']]))) {
				$category = $firstfollowups[$myform['id']]->category;
			}

			if(!empty($category)) {
				$array[$myform->source]['remarks'][$category]++;
				$array[$myform->source]['remarks']['Total']++;
			}
        }
        

        $newarr =array();
        foreach($array as $key => $val) {
        	//$newarr
        	foreach($val['remarks'] as $k => $v)
        	{
        		$newarr[$k][$key] = $v;
        		//dd($k);
        	}
        	$newarr['No. Of Leads on LMS '][$key] = $val['total'];
        	$newarr['Unique In Source '][$key] = $val['uniqueinsourse'];
        	if(empty($val['unique'])) {
        		$val['unique'] = 0;
        	}
        	$newarr['Unique Leads'][$key] = $val['unique'];
        }

        $sourcedata = $newarr;

        //Mail Content
        $content = "<h4>Lead Quality Report</h4>
        <div class='table-responsive'>
          <table class='table table-striped table-bordered' border='1' style='border-collapse: collapse;'>";

          	if(!empty($sourcedata)):
            $content .= "<thead>
              <tr>
                <th>Lead Remarks</th>";
                
                foreach(reset($sourcedata) as $key => $list):
                  $content .= "<th> {$key}</th>";
                endforeach;
                
            $content .= "</tr>
            </thead>
            <tbody>";
              foreach($sourcedata as $key => $val):
              $content .= "<tr>
                <td> {$key}</td>";
                foreach(reset($sourcedata) as $k => $list):
                  $content .= "<td> {$val[$k]}</td>";
                endforeach;
                
              $content .= "</tr>";
              
              endforeach;
              

            $content .= "</tbody>";
            else:
            $content .= "<tr class='no-data'>
                <td colspan='4'>No Record Found</td>
            </tr>";
            endif;
          $content .= "</table>
        </div>";
		

        //Send Welcome Mail to User
        $array = [
            "sender" => [ 
                "name" => "JIMS LMS PORTAL",  "email"=> "admissions@jimsindia.org"
            ], 
            // "to" => [ 
            //     ["name" => "Labhya Gupta",  "email"=> "labhya.gupta@jimsindia.org"],
            //     ["name" => "Swati Goel",  "email"=> "swati.goel@jimsindia.org"]
            // ], 
            "to" => [ 
                ["name" => "Labhay Gupta",  "email"=> "labhya.gupta@jimsindia.org"],
                ["name" => "Swati Goel",  "email"=> "swati.goel@jimsindia.org"],
                ["name" => "Manish Arora",  "email"=> "manish.arora@jimsindia.org"],
                ["name" => "Rajkamal",  "email"=> "rajkamal@jimsindia.org"]
            ],

            'subject' => "LMS Lead Quality Weekly Report From ".$startdate." To ".$enddate,

            "htmlContent" => "<html><head></head><body>{$content}</body></html>"

        ]; //Based on format custom mail
            
        $response = sendSMTPMAIL($array);

        return 'success';
	}

	public function automateDailyCounsellorReportMail(Request $request)
	{
		$currentdate = date('Y-m-d');
		$startdate = $enddate = date('Y-m-d', strtotime('-1 day', strtotime($currentdate)));

		$allfollowupbydate = array();
			
		$counsellors = User::whereHas('roles', function($q)
                    {
                        $q->where('name', '=', 'Counsellor');
                        $q->orWhere('name', '=', 'FrontDesk');
                    })
				->where('status' , 1)
                ->whereNotIn('id', [5,6, 15,16,17])
                ->get();
            
        foreach ($counsellors as $counsellor) {
        	$totalfollowup = 0; 
			$opencalls = 0;
            $closedcalls = 0;
            $completedcalls = 0;

			for ($i=1; $i <= 5; $i++) { 
	        	$tablename = followuptablename($i);

	        	$count = followuptable($i)
	        		->where("$tablename.status",1)
	        		->where("counsellor_id",$counsellor->id)
                    ->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->count();

                $varname = 'followup'.$i;
                $$varname = $count;
                $totalfollowup += $count; 
                

                $followupStatusCount = followuptable($i)
                	->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
                	->join('forms', 'forms.id', '=', "$tablename.form_id")
                	->where("counsellor_id",$counsellor->id)
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->where("$tablename.status",1)
                    ->where('message_type' ,'=', '')
                    ->where('reentry' ,'=', 0)
                    ->groupBy('forms.status')
                    ->get()->keyBy('status');

                
                $opencalls = $opencalls + (!empty($followupStatusCount['open']) ? $followupStatusCount['open']->counter : 0);
                $closedcalls = $closedcalls + (!empty($followupStatusCount['closed']) ? $followupStatusCount['closed']->counter : 0);
                $opencalls = $opencalls + (!empty($followupStatusCount['complete']) ? $followupStatusCount['complete']->counter : 0);

	        }
	        
        	
			$followup = array('Name' => $counsellor->firstname, 'Start Date' => $startdate, 'End Date' => $enddate, 'First Followup' => $followup1, 'Second Followup' => $followup2, 'Third Followup' => $followup3, 'Fourth Followup' => $followup4, 'Fifth Followup' => $followup5,'Open Calls' => $opencalls,'Closed Calls' => $closedcalls,'Completed Calls' => $completedcalls, 'Total Calls' => $totalfollowup);

			//Pass it to main/base array
        	array_push($allfollowupbydate, $followup);
        	
        }		

        //Mail Content
        $content = "<h4>Counsellor Daily Report</h4>
        <div class='table-responsive'>
          <table class='table table-striped table-bordered' border='1' width='800' style='border-collapse: collapse; text-align:center;'>";

          	if(!empty($allfollowupbydate)):
            $content .= "<thead>
              <tr>";
                
                foreach(reset($allfollowupbydate) as $key => $list):
                  $content .= "<th> {$key}</th>";
                endforeach;
                
            $content .= "</tr>
            </thead>
            <tbody>";
              foreach($allfollowupbydate as $key => $val):
              $content .= "<tr>";
                foreach(reset($allfollowupbydate) as $k => $list):
                  $content .= "<td> {$val[$k]}</td>";
                endforeach;
                
              $content .= "</tr>";
              
              endforeach;
              

            $content .= "</tbody>";
            else:
            $content .= "<tr class='no-data'>
                <td colspan='4'>No Record Found</td>
            </tr>";
            endif;
          $content .= "</table>
        </div>";


		//Send Mail
        $array = [
            "sender" => [ 
                "name" => "JIMS LMS PORTAL",  "email"=> "admissions@jimsindia.org"
            ], 
            "to" => [ 
                ["name" => "Labhay Gupta",  "email"=> "labhya.gupta@jimsindia.org"],
                ["name" => "Swati Goel",  "email"=> "swati.goel@jimsindia.org"],
                ["name" => "Manish Arora",  "email"=> "manish.arora@jimsindia.org"],
                ["name" => "Rajkamal",  "email"=> "rajkamal@jimsindia.org"]
            ],
            'subject' => "LMS Counsellor Daily Call Report From ".$startdate." To ".$enddate,

            "htmlContent" => "<html><head></head><body>{$content}</body></html>"

        ]; //Based on format custom mail
            
        $response = sendSMTPMAIL($array);

        return 'success';
	}

	public function automateDailyCounsellorQualityReportMail(Request $request)
	{
		$currentdate = date('Y-m-d');
		$startdate = $enddate = date('Y-m-d', strtotime('-1 day', strtotime($currentdate)));

		$array      = array();

		$arr = array(
			'Interested for Admission' => 0,
			'Need More Time' => 0,
			'Form Filled' => 0,
			'Will Check after Nov CAT' => 0,
			'Will Check after Dec MAT' => 0,
			'Will Check after Jan CMAT' => 0,
			'Will Check after Feb MAT' => 0,
			'Will Check after May MAT' => 0,
			'Will Check after XAT' => 0,
			'Will Check after GMAT'=> 0,
	        /*'Interested' => 0,*/
	        'Callback' => 0,
	        'Looking For Another Course' => 0,
	        'Concerned Person Not Available' => 0,
	        'Call Not Picked' => 0,
	        'Call Disconnected' => 0,
	        'Not Reachable' => 0,
	        'Switch Off' => 0,
	        'Not Interested' => 0,
	        'Wrong Number' => 0,
	        'Not Eligible' => 0,
	        'Junk' => 0,
	        'MBA Only' => 0,
	        'Drop This Year' => 0,
	        'Not Appeared In Any Entarance' => 0,
	        'Admission Taken in Another College' => 0,
	        'Send to Whatsapp' => 0,
	        'Golden Calls' => 0,
	        'Alloted' => 0,
	        'Total' => 0,
	    );

	    //counsellor list
	    $counsellors = User::whereHas(
                            'roles', function($q){
                                $q->where('name', 'Counsellor');
                                $q->orWhere('name', 'FrontDesk');
                            }
                        )
	    				->where('status',1)
	    				->get();

        //$counsellorarray = $counsellors->keyBy('id')->toArray();
        foreach($counsellors as $list) {
        	$array[$list->id] = $arr;
        }

	    for ($i=1; $i <= 5; $i++) { 
            $tablename = followuptablename($i);
        
            $followupcalls = followuptable($i)
            			->select('id','counsellor_id','form_id','level','category')
                        ->where('reentry', '=', 0)
						->where('status', '=', 1)
						->where('message_type', '=', '')
						->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
                    	->get()->keyBy('form_id')->toArray();

            foreach($followupcalls as $call) {
            	$array[$call->counsellor_id][$call->category]++;
            	$array[$call->counsellor_id]['Total']++;
            }
        }


        $counsellordata = $array;
        //dd($counsellordata);

        //Mail Content
        $content = "<h4>Counsellor Call Quality Report</h4>
        <div class='table-responsive'>
          <table class='table table-striped table-bordered' border='1' style='border-collapse: collapse;'>";

          	if(!empty($counsellordata)):
            $content .= "<thead>
              <tr>
                <th>Category</th>";
                
                foreach($counsellors as $key => $list):
                  $content .= "<th> {$list->firstname}</th>";
                endforeach;
                
            $content .= "</tr>
            </thead>
            <tbody>";
              foreach(reset($counsellordata) as $key => $val):
              	//dd($key);
              $content .= "<tr>
                <td> {$key}</td>";
                // foreach($counsellordata as $k => $list):
                // 	dd($list);
                //   $content .= "<td> {$val[$k]}</td>";
                // endforeach;

                foreach($counsellors as $list):
		        	$content .= "<td>". $counsellordata[$list->id][$key] ." </td>";
		        endforeach;
                
              $content .= "</tr>";
              
              endforeach;
              

            $content .= "</tbody>";
            else:
            $content .= "<tr class='no-data'>
                <td colspan='4'>No Record Found</td>
            </tr>";
            endif;
          $content .= "</table>
        </div>";
		

        //Send Mail
        $array = [
            "sender" => [ 
                "name" => "JIMS LMS PORTAL",  "email"=> "admissions@jimsindia.org"
            ], 
            "to" => [ 
                ["name" => "Labhay Gupta",  "email"=> "labhya.gupta@jimsindia.org"],
                ["name" => "Swati Goel",  "email"=> "swati.goel@jimsindia.org"],
                ["name" => "Manish Arora",  "email"=> "manish.arora@jimsindia.org"],
                ["name" => "Rajkamal",  "email"=> "rajkamal@jimsindia.org"]
            ],
            'subject' => "LMS Counsellor Call Quality Report From ".$startdate." To ".$enddate,

            "htmlContent" => "<html><head></head><body>{$content}</body></html>"

        ]; //Based on format custom mail
            
        $response = sendSMTPMAIL($array);

        return 'success';
    
	}
}