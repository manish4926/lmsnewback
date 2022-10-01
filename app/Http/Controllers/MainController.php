<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Config;


use App\Model\User;
use App\Model\Role;
use App\Model\MyForm;
use App\Model\Followup;
use App\Model\Admissionform;
use App\Model\Sms;
use App\Model\Option;
use App\Model\Admission;
use App\Model\Reentry;
use App\Model\Recall;
use App\Model\WelcomeMailer;

use Auth;
use Session;
use Excel;
use SendinBlue;
use View;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Carbon\Carbon;
use App\Http\Requests;
//use App\Traits\ZohoInsertQueryTrait;
use App\Traits\AssignQueryTrait;

use App\Exports\RemarksReportExport;
use App\Exports\CompleteFormsExport;
use App\Exports\UniqueFormsExport;

class MainController extends Controller
{
    use AssignQueryTrait;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $profile = !empty($user) ? $user->getProfile() : '';
            if(empty($profile)) {
                $profile = array();
                $profile['theme']['options'] = '';
                $profile['sidebar']['options'] = '';
                $profile['dataversion']['options'] = '';
            }
            $this->userProfile = $profile;
            View::share(['userProfile' => $this->userProfile ]);
            return $next($request);
        });

        $this->current_batch = config('site_vars.currennt_batch');
    }


    public function dashboard(Request $request)		//Dashboard
	{
		if (Auth::guest()) {
		    return redirect()->route('login');
		}


		$user = Auth::user();
        if($user->hasRole('Admin') == false) {
            return redirect()->route('followuplist',['followupnum' => 1]);
        }
        /*$totalregistrations = MyForm::count();
        $totalcompletion    = MyForm::where('status' ,'=', 'complete')
                                        ->count();

        $pendingqueries     = Followup::where('level' ,'=', 0)
                                        ->where('status' ,'=', 0)
                                        ->count();   

        $pendingpgdmqueries     = Followup::join('forms', 'forms.id', '=', 'followups.form_id')
                                        ->where('level' ,'=', 0)
                                        ->where('followups.status' ,'=', 0)
                                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA'])
                                        ->count(); 

        $pendingipqueries     = Followup::join('forms', 'forms.id', '=', 'followups.form_id')
                                        ->where('level' ,'=', 0)
                                        ->where('followups.status' ,'=', 0)
                                        ->whereIn('course', ['BBA', 'BCA', 'MCA'])
                                        ->count(); 

        $totalconversion    = Admissionform::count();*/

      


        /*$counsellorprogress = User::whereHas('roles', function($q)
                                {
                                    $q->where('name', '=', 'Counsellor');
                                    $q->orWhere('name', '=', 'FrontDesk');
                                })
                                ->where('status' , 1)
                                ->whereNotIn('id', [5,6,7, 15,16,17])->get();

        foreach($counsellorprogress as $counsellor){
            
            $counsellor->totalassigned = Followup::where('message_type', "")
                                                    ->Where('reentry', 0)
                                                    ->Where('counsellor_id', $counsellor->id)
                                                    ->count();

            $counsellor->totalcompleted = Followup::where('status', 1)
                                                    ->Where('message_type', "")
                                                    ->Where('reentry', 0)
                                                    ->Where('counsellor_id', $counsellor->id)
                                                    ->count();

            $counsellor->perc = $counsellor->totalassigned > 0 ? round(($counsellor->totalcompleted * 100) / $counsellor->totalassigned) : 0;

        }*/

        

        /* For Graph*/
        /*$dates = get7DaysDates(8, 'Y-m-d');
        $google_array = array();

        $counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get();

        $basearr = array('Days');
        foreach ($counsellors as $counsellor) {
            array_push($basearr, $counsellor->firstname." ".$counsellor->lastname);
            //dd($counsellor->followupcountbydate('2018-11-15')->counter);
        }

        array_push($google_array,$basearr);
        

        foreach ($dates as $key => $date) {
            $arr = array();
            array_push($arr, $date);
            foreach ($counsellors as $counsellor) {
                ///dd($date);
                if(!empty($counsellor->followupcountbydate($date))) {
                    $data1 = (int)$counsellor->followupcountbydate($date)->counter;  
                } else {
                    $data1 = 0;
                }
                
                array_push($arr, $data1);
            }
            array_push($google_array, $arr);
        }*/

        /*Source Graph*/
        //$sourcedata_chart = getSourceDataforGraph();

        /*Remarks Graph*/
        //$remarksdata_chart = getRemarksData();
        
        $totalregistrations = 0;
        $totalcompletion = 0;
        $pendingqueries = 0;
        $pendingpgdmqueries = 0;
        $pendingpgdmqueries = $pendingipqueries = $totalconversion = $counsellorprogress = $google_array = $sourcedata_chart = $remarksdata_chart = null;
    	return view('dashboard',compact('request','user','totalregistrations','totalcompletion','pendingqueries','pendingpgdmqueries','pendingipqueries','totalconversion','counsellorprogress','google_array', 'sourcedata_chart', 'remarksdata_chart'));
	}

    public function counsellorProgress(Request $request)
    {
        $user = Auth::user();
        $counsellorprogress = User::whereHas('roles', function($q)
                                {
                                    $q->where('name', '=', 'Counsellor');
                                    $q->orWhere('name', '=', 'FrontDesk');
                                })
                                ->where('status' , 1)
                                ->whereNotIn('id', [5,6,7, 15,16,17,34])->get();

        //dd($counsellorprogress);

        foreach($counsellorprogress as $counsellor){

            $totalassigned = $totalcompleted = 0;

            for ($i=1; $i < 5; $i++) { 
                $tablename = followuptablename($i);
            
                $followuptotalassigned = followuptable($i)->where('message_type', "")
                                                    ->Where('reentry', 0)
                                                    ->Where('counsellor_id', $counsellor->id)
                                                    ->count();
                $totalassigned = $totalassigned + $followuptotalassigned;

                $followuptotalcompleted = followuptable($i)->where('status', 1)
                                                    ->Where('message_type', "")
                                                    ->Where('reentry', 0)
                                                    ->Where('counsellor_id', $counsellor->id)
                                                    ->count();

                $totalcompleted = $totalcompleted + $followuptotalcompleted;


            }

            $counsellor->totalassigned = $totalassigned;

            $counsellor->totalcompleted = $totalcompleted;

            $counsellor->perc = $counsellor->totalassigned > 0 ? round(($counsellor->totalcompleted * 100) / $counsellor->totalassigned) : 0;

        }

        return view('admin.counsellorprogress',compact('request','user','counsellorprogress'));
    }


    public function counsellorGraph(Request $request)
    {
        $user = Auth::user();
        /* For Graph*/
        $dates = get7DaysDates(7, 'Y-m-d');
        $google_array = array();

        $counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17,34])
                    ->get();

        $basearr = array('Days');
        foreach ($counsellors as $counsellor) {
            array_push($basearr, $counsellor->firstname." ".$counsellor->lastname);
            //dd($counsellor->followupcountbydate('2018-11-15')->counter);
        }

        array_push($google_array,$basearr);
        

        foreach ($dates as $key => $date) {
            $arr = array();
            array_push($arr, $date);
            foreach ($counsellors as $counsellor) {

                $totalcallsbycounsellor = 0;
                for ($i=1; $i < 5; $i++) { 
                    $tablename = followuptablename($i);
                
                    $followupcalls = followuptable($i)
                                ->select('counsellor_id',DB::raw('count(*) as counter'))
                                ->Where('counsellor_id', $counsellor->id)
                                ->where(DB::raw('Date(updated_at)') ,'=', $date)
                                ->where('status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0)
                                ->count();
                    
                    $totalcallsbycounsellor += $followupcalls;

                }
                
                array_push($arr, $totalcallsbycounsellor);
            }
            array_push($google_array, $arr);
        }
        return view('counsellorgraph',compact('request','user','google_array'));
    }
	public function addForm(Request $request)		//Add New Form Manually
	{
		$user = Auth::user();
		
    	return view('addform',compact('request','user'));
	}

	public function addFormSubmit(Request $request)		//Add New Form Manually
	{
		$user = Auth::user();
        $checkduplicate = MyForm::where('email', '=', $request->email)->Where('status', 'open')->first();
        $current_time = Carbon::now();

        //$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);
        

        if(empty($checkduplicate)) {
            $myform = new MyForm;
            $myform->name   = $request->name;
            $myform->email  = $request->email;
            $myform->phone  = $request->phone;
            $myform->city   = $request->city;
            $myform->course = $request->course;
            $myform->source = $request->source;
            $myform->query  = $request->message;
            $myform->posted_at = $current_time;
            $myform->status = 'open';
            $myform->save();
            Session::flash('successMessage', 'Form Added Successfully');

            $lastinsertedid = $myform->id;
            //addVerificationMail($lastinsertedid, $request->email);

            if($user->hasRole('FrontDesk')) {
                
                followuptable(1)->insert([['counsellor_id' => $user->id, 'form_id' => $lastinsertedid,'level' => 0]]);

                MyForm::where('id', $lastinsertedid)
                            ->increment('followcounts');
                return redirect()->route('followupdetail', ['id' => $lastinsertedid]);
            }
            else {
                return redirect()->back();    
            }
        }
        else {
            Session::flash('errorMessage', 'Form Already Exist.');
            return redirect()->route('followupdetail', ['id' => $checkduplicate->id]);
            
        }
		
	}

	public function addBulkForm(Request $request)		//Add Form Using Excel
	{
		$user = Auth::user();
    	return view('bulkform',compact('request','user'));
	}

	public function addBulkFormSubmit(Request $request)		//Add Form Using Excel
	{
		$user = Auth::user();
    	$file = $request->file('file');
        //$now = Carbon::now('utc')->toDateTimeString();
        
        // Insert Products Excel File and Upload to Database
        $fileformat = $file->getClientOriginalExtension();
        if($fileformat == 'csv') {
            $path = $file->getPathName();
            $rows = array_map('str_getcsv', file($path));

            $rowcount   = count($rows);
            for ($i=0; $i <= $rowcount -1; $i++) { 
                if($i == 0) { continue; }
                $myform = new MyForm;
                $myform->name      = $rows[$i][0];
                $myform->email     = $rows[$i][1];
                $myform->phone     = $rows[$i][2];
                $myform->city      = $rows[$i][3];
                $myform->course    = $rows[$i][4];
                $myform->query     = $rows[$i][5];
                $myform->source    = ucwords(strtolower($rows[$i][6]));
                $myform->status    = 'open';
                $myform->posted_at = date('Y-m-d 00:00:00', strtotime($rows[$i][7]));
                $myform->save();

                $lastid = $myform->id;

                $this->allotFirstQueryToCounsellor($request->name, $request->email, $request->phone, $request->source, $lastid);

                sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);
                sendtoEmailAutomation($request->Email, $request->source);
                
            }
        }
        /*if(($fileformat == 'xls') or ($fileformat == 'xlsx'))
        {
            $excel = Excel::selectSheetsByIndex('0')->load($file, function($reader) {
            })->get();
            $data = array();
            //dd($excel[0]->items);
            
            foreach ($excel as $row) {
                $myform = new MyForm;
                $myform->name      = $row->name;
                $myform->email     = $row->email;
                $myform->phone     = $row->phone;
                $myform->city      = $row->city;
                $myform->course    = $row->course;
                $myform->query     = $row->message;
                $myform->source    = $row->source;
                $myform->status    = 'open';
                $myform->posted_at = date('Y-m-d 00:00:00', strtotime($row->date));
                $myform->save();
            }

        }*/
            
        
        return "Success";
	}

    public function addBulkResponse(Request $request)       //Add Form Using Excel
    {
        $user = Auth::user();
        return view('bulkresponse',compact('request','user'));
    }

    public function addBulkResponseSubmit(Request $request)     //Add Form Using Excel
    {
        $user = Auth::user();
        $file = $request->file('file');
        //$now = Carbon::now('utc')->toDateTimeString();
        
        // Insert Products Excel File and Upload to Database
        $fileformat = $file->getClientOriginalExtension();
        if(($fileformat == 'xls') or ($fileformat == 'xlsx'))
        {
            $excel = Excel::load($file, function($reader) {
            })->get();
            $data = array();

            //dd($excel[0]->items);
            foreach ($excel as $row) {
                $form = "";
                if(!empty($row->status)) {
                    if(!empty($row->query_id)){
                    $form = MyForm::where('qid' ,'=', $row->query_id)
                                ->where('followcounts', '=', 1)
                                ->where('dupcheck', '=', 0)
                                ->groupBy('email')
                                ->orderBy('posted_at', 'desc')
                                ->first();

                    }
                    if($form == null) {
                        $form = MyForm::where('email' ,'=', $row->email)
                                ->where('followcounts', '=', 1)
                                ->where('dupcheck', '=', 0)
                                ->groupBy('email')
                                ->orderBy('posted_at', 'desc')
                                ->first();
                    }
                    if($form == null) {
                        $form = MyForm::where('phone' ,'=', $row->phone)
                                ->where('followcounts', '=', 1)
                                ->where('dupcheck', '=', 0)
                                ->groupBy('email')
                                ->orderBy('posted_at', 'desc')
                                ->first();
                    }
                

                
                    if($form != null) {
                        Followup::where('form_id', $form->id)
                        ->Where('level', '=', 0)
                        ->Where('comment', '=', '')
                        ->update(['comment' => $row->status, 'status' => 1]);    
                    }
                
                }

            }

        }
            
        
        return "Success";
    }


    public function addBulkResponseTwoSubmit(Request $request)     //Add Form Using Excel
    {
        $user = Auth::user();
        $file = $request->file('file');
        //$now = Carbon::now('utc')->toDateTimeString();
        
        // Insert Products Excel File and Upload to Database
        $fileformat = $file->getClientOriginalExtension();

        if(($fileformat == 'xls') or ($fileformat == 'xlsx'))
        {

            $excel = Excel::load($file, function($reader) {
            })->get();
            $data = array();

            //dd($excel[0]->items);
            foreach ($excel as $row) {
                $form = "";
                //dd($row);
                $myform = new MyForm;
                $myform->name      = $row->name;
                $myform->email     = $row->email;
                $myform->phone     = $row->phone;
                $myform->city      = $row->location;
                $myform->course    = $row->course;
                $myform->query     = $row->remarks;
                $myform->source    = $row->source;
                $myform->status    = 'open';
                $myform->followcounts    = !empty($row->counsellor) ? 1 : 0;
                $myform->posted_at = date('Y-m-d 00:00:00', strtotime($row->date));
                $myform->save();

                $lastinsertedid = $myform->id;

                if(!empty($row->counsellor)) {
                    $followup = new Followup;
                    $followup->counsellor_id = $row->counsellor;
                    $followup->form_id       = $lastinsertedid;
                    $followup->comment       = $row->firstfollowup;
                    $followup->level         = 0;
                    $followup->status        = 1;
                    $followup->save();    
                }

                
            }

        }
            
        
        return "Success";
    }


	
	public function viewForm(Request $request)		//View Forms
	{
		$user = Auth::user();

		// $myform = MyForm::select('id','name','email','phone','city','source','pagepath','course','query', DB::raw('count(email) as total'))
		// 		->groupBy('email')
		// 		->orderBy('created_at', 'asc')
        //       ->get();

        

        $myform = DB::select("SELECT tbl.*, tbl1.total from forms tbl INNER JOIN ( SELECT id,email, min(id) minid, count(email) as total FROM forms GROUP BY email ) tbl1 ON tbl1.email = tbl.email WHERE tbl1.minid = tbl.id");

		
    	return view('viewforms',compact('request','user','myform'));
	}

	public function getUserDetail(Request $request)		//View Forms
	{
		$email 		= $request->email;
		$contacts 	= MyForm::select('phone','source','posted_at','created_at')
						->where('email', '=', $email)
						->orderBy('id', 'asc')
						->get();

        foreach($contacts as $contact) {
            $contact->date = date('d/m/Y',strtotime($contact->posted_at));
        }
		if($request->ajax()){
		  return $contacts;
		}
	}


    public function sourceData(Request $request)     //Analyse Records
    {
        $user = Auth::user();
        $search = $request->search;

        $myform = MyForm::select('id','name','email','phone','city','source','course','query', DB::raw('count(email) as total'))
                ->orderBy('created_at')
                ->when($search, function ($myform) use ($search) {
                            if($search != 'All') {
                                return $myform->where('source', $search);    
                            }
                            
                        })
                /*->when($status, function ($myform) use ($status) {
                            if($search != 'All') {
                                return $myform->where('status', $status);    
                            }
                            
                        })*/
                ->groupBy('email')
                ->get();
                
        $sources = MyForm::select('source')
                    ->groupBy('source')
                    ->get();
        return view('sourcedata',compact('request','user','myform','sources'));
    }

    public function assignData(Request $request)      //View Forms
    {
        
        $user = Auth::user();
        $followupnum = $request->followupnum;

        // DB::update("UPDATE forms LEFT JOIN ( SELECT id,COUNT(id) AS counter FROM forms GROUP BY email ) dup ON forms.id = dup.id SET `dupcheck`=1  WHERE counter IS NULL");
        
        // MyForm::where('source', 'Student Zone')
        //     ->WHERE('dupcheck' , 1)
        //     ->update(['dupcheck' => 0]);

        $remarks = $request->remarks;
        
        if($followupnum == 1) {
            
            MainController::removeDuplicateFromAssignData();


            $followupquery = MyForm::select('id','name','email','phone','city','source','course','pagepath','query','followcounts','posted_at', DB::raw('count(email) as total'))
                ->where('followcounts', '=', 0)
                ->where('dupcheck', '=', 0)
                ->where('status', '=', 'open')
                ->groupBy('email')
                ->orderBy('posted_at', 'desc');

            
            $followuplist = $followupquery->get();
        }  else {

            $tablename = followuptablename($followupnum-1);

            $followuplist = MyForm::select('forms.id','name','email','phone','city','source','course','pagepath','query','followcounts','posted_at',"$tablename.reentry","$tablename.counsellor_id","$tablename.comment","$tablename.status as followstatus","$tablename.updated_at as followdate", DB::raw('count(email) as total'))
                ->join("$tablename", 'forms.id', '=', "$tablename.form_id")
                ->where('followcounts', '=', $followupnum-1)
                ->where("$tablename.reentry", '=', 0)
                ->where("$tablename.status", '=', 1)
                ->where('dupcheck', '=', 0)
                ->where('forms.status', '=', 'open')
                ->when($remarks, function ($followuplist) use ($remarks) {
                            if(!empty($remarks)) {
                                return $followuplist->where('comment', 'like', "%".$remarks."%");
                            }
                            
                        })
                ->groupBy('email')
                ->orderBy('forms.posted_at', 'desc')
                ->get();
        }
        
        

        $counsellors = User::whereHas(
                            'roles', function($q){
                                $q->where('name', 'Counsellor');
                                $q->orWhere('name', 'FrontDesk');
                            }
                        )->get();

        $counsellorarray = $counsellors->keyBy('id')->toArray();

        return view('assigndata',compact('request','user','followuplist','counsellors','counsellorarray', 'followupnum'));
    }

    // public function removeDuplicateFromAssignData() {
    //     $followupquery = MyForm::select('id','name','email','phone','city','source','course','pagepath','query','followcounts','posted_at', DB::raw('count(email) as total'))
    //             ->where('followcounts', '=', 0)
    //             ->where('dupcheck', '=', 0)
    //             ->where('status', '=', 'open')
    //             ->groupBy('email')
    //             ->orderBy('posted_at', 'desc');

    //     $list = $followupquery->get();

    //     foreach ($list as $key => $value) {

    //         $totalqueryfound = $value->total;

    //         while($totalqueryfound > 0) {
    //             //DB::enableQueryLog();
    //             $query = MyForm::select('id','name','email','posted_at', DB::raw('count(email) as total'))
    //             ->where('email', $value->email)
    //             // ->where('dupcheck', '=', 0)
    //             ->where('status', '=', 'open')
    //             ->groupBy('email')
    //             ->first();

    //             if($query->total > 1) {
                    

    //                     $query1 = MyForm::select('id','name','email','phone','city','source','course','pagepath','query','followcounts','posted_at')
    //                         ->where('email', $value->email)
    //                         ->where('dupcheck', '=', 0)
    //                         ->where('followcounts', '=', 0)
    //                         ->where('status', '=', 'open')
    //                         ->orderBy('posted_at', 'desc')
    //                         ->first();
                        
    //                     MyForm::where('id', $query1->id)
    //                         ->update(['dupcheck' => 1]);
                    
                
    //             }  

                
    //             $totalqueryfound--;
    //         }
            
    //     }
    // }

    public function removeDuplicateFromAssignData() {
        $followupquery = MyForm::select('id','name','email','phone','city','source','course','pagepath','query','followcounts','posted_at')
                ->where('followcounts', '=', 0)
                ->where('dupcheck', '=', 0)
                ->where('status', '=', 'open')
                ->orderBy('posted_at', 'desc');

        $list = $followupquery->get();

        foreach ($list as $key => $value) {

            

            $counter = MyForm::where('email', $value->email)
                        ->where('status', '=', 'open')
                        ->where('id', '<', $value->id)
                        ->where('dupcheck', '=', 0)
                        ->where('status', '=', 'open')
                        ->count(); 

            if($counter >= 1) {
                MyForm::where('id', $value->id)
                        ->update(['dupcheck' => 1]);
            }

            
        }
    }

    public function assignDataSubmit(Request $request)     //Analyse Records
    {
        $user = Auth::user();
        $forms = json_decode($request->forms);
        $current_time = Carbon::now();
        
        $data = array();
        foreach($forms as $form) {
            array_push($data,array('counsellor_id'=> $request->counsellor, 'form_id'=> $form, 'level'=> $request->level, 'created_at' => $current_time, 'updated_at' => $current_time));
        }

        followuptable($request->level)->insert($data);

        MyForm::whereIn('id', $forms)->increment('followcounts');

        Session::flash('successMessage', 'Data Successfully Assigned');
        return redirect()->back();
    }

    public function updateFormStatusSubmit(Request $request)     //Analyse Records
    {
        $user = Auth::user();
        $forms = json_decode($request->forms);
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        $data = array();
        $idlist = array();
        foreach ($forms as $form) {
            array_push($data,array('counsellor_id'=> $user->id, 'form_id'=> $form, 'level'=> $request->level, 'status'=> 1, 'comment'=>'Query Closed By Admin('.$user->firstname.')'));
            
            array_push($idlist, $form);
            /*MyForm::where('id', $form)
            ->update(['status' => $request->status]);*/
        }   

        if($followupnum == 0) { $newfollowcount = 1; } else { $newfollowcount = $followupnum; }
        MyForm::whereIn('id', $idlist)
            ->update(['status' => $request->status, 'followcounts' => $newfollowcount]);

        followuptable($followupnum)->insert($data);     

        Session::flash('successMessage', 'Data Successfully Assigned');
        return redirect()->back();
    }

    public function updateFormStatusFromCounsellorSubmit(Request $request)     //Update Form Status From Counsellor Page
    {
        $user = Auth::user();
        $forms = json_decode($request->forms);
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        $data = array();
        $idlist = array();

        foreach ($forms as $form) {

            array_push($data,array('counsellor_id'=> $user->id, 'form_id'=> $form->formid, 'level'=> $form->formlevel, 'status'=> 1, 'comment'=>'Query Closed By Admin('.$user->firstname.')'));
            
            array_push($idlist, $form->formid);
        } 

        followuptable($followupnum)->whereIn('form_id', $idlist)
            ->Where('status',0)
            ->delete();  //Delete entry from previous counsellor
            
        MyForm::whereIn('id', $idlist)
            ->update(['status' => $request->status]);
            
        followuptable($followupnum)->insert($data);     

        Session::flash('successMessage', 'Data Successfully Assigned');
        return redirect()->back();
    }


    public function assignedData(Request $request)     //Analyse Records
    {
        $user        = Auth::user();
        $counsellors = getCounsellor();
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        $startdate   = date('Y-m-d', strtotime('-7 days'));
        $enddate     = date('Y-m-d');


        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    //->where("$tablename.status",0)
                    ->whereBetween(DB::raw("Date($tablename.created_at)"), [$startdate, $enddate])
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get();


        if($followupnum > 1) {
            $lastcategoryall = followuptable($followupnum-1)->where("status",1)->where("counsellor_id",$user->id)->get()->keyBy('form_id')->toArray();

            foreach($list as $forms) {
                //$lastcategory = followuptable($followupnum-1)->where("status",1)->where("form_id",$forms->form_id)->first();
                //$forms->lastcategory = $lastcategory->category;
                $forms->lastcategory = !empty($lastcategoryall[$forms->form_id]) ? $lastcategoryall[$forms->form_id]->category : "";
                //dd($forms->lastcategory);
            } 
        }
                    

        return view('assigneddata',compact('request','user','list', 'counsellors', 'followupnum'));

        // $user = Auth::user();
        // $counsellors = getCounsellor();
        // /*$list = Followup::where('reentry',0)
        //             ->get();*/
        // $list = Followup::select('followups.*', 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
        //             ->join('forms', 'forms.id', '=', 'followups.form_id')
        //             ->where('reentry',0)
        //             ->get();

        // return view('followuplist',compact('request','user','list', 'counsellors'));
    }

    public function followupList(Request $request)     
    {
        $user        = Auth::user();
        $counsellors = getCounsellor();
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

//dd($followupnum);
        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where('counsellor_id',$user->id)
                    ->where("$tablename.status",0)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get();


        if($followupnum > 1) {
            $lastcategoryall = followuptable($followupnum-1)->where("status",1)->where("counsellor_id",$user->id)->get()->keyBy('form_id')->toArray();

            foreach($list as $forms) {
                //$lastcategory = followuptable($followupnum-1)->where("status",1)->where("form_id",$forms->form_id)->first();
                //$forms->lastcategory = $lastcategory->category;
                $forms->lastcategory = !empty($lastcategoryall[$forms->form_id]) ? $lastcategoryall[$forms->form_id]->category : "";
                //dd($forms->lastcategory);
            } 
        }
                    

        return view('followuplist',compact('request','user','list', 'counsellors', 'followupnum'));
    }

    public function followupDetail(Request $request)     
    {
        $user = Auth::user();
        $id = $request->id;

        $formdetails = MyForm::where('id', '=', $id)->first();
        $followupnum = $formdetails->followcounts;


        $admissionformcheck = Admissionform::where('email', '=', $formdetails->email)
                                ->orWhere('phone', '=', $formdetails->phone)
                                ->first();

        $checkuserassigned = followuptable($followupnum)->where('form_id', '=', $id)
                                ->Where('counsellor_id', '=', $user->id)
                                ->count();

        $currentfollowup = followuptable($followupnum)->where('form_id', '=', $id)
                        ->Where('message_type', '=', '')
                        ->orderBy('id', 'desc')
                        ->first();

        $previousform = MyForm::select('id', 'status')
                                ->where('email' , $formdetails->email)
                                ->where('dupcheck', 0)
                                ->where('id', '!=' , $id)
                                ->first();
        
        if(!empty($previousform)) {
            $followup2 = followuptable(2)->where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc');

            $followup3 = followuptable(3)->where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc');

            $followup4 = followuptable(4)->where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc');

            $followup5 = followuptable(5)->where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc');

            $previousformfollowups = followuptable(1)->where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc')
                    ->union($followup2)
                    ->union($followup3)
                    ->union($followup4)
                    ->union($followup5)
                    ->get();
        } else {
            $previousformfollowups = null;
        }


        $reentryfollowup1 = followuptable(1)->where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $reentryfollowup2 = followuptable(2)->where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $reentryfollowup3 = followuptable(3)->where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $reentryfollowup4 = followuptable(4)->where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $reentryfollowup5 = followuptable(5)->where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $reentrydata = Reentry::where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc')
                    ->unionAll($reentryfollowup1)
                    ->unionAll($reentryfollowup2)
                    ->unionAll($reentryfollowup3)
                    ->unionAll($reentryfollowup4)
                    ->unionAll($reentryfollowup5)
                    ->get();

        $hotlead_conversations = DB::table('hotleads_conversations')->where('form_id',$id)->orderBy('id', 'ASC')->get();


        $unfollowupcounsellor = followuptable($followupnum)->where('form_id', '=', $id)
                            ->Where('comment', '=', null)
                            ->Where('message_type', '=', '')
                            ->orderBy('id', 'desc')
                            ->first();

        $counsellorlist = getCounsellor();
        $unfollowupcounsellorid = !empty($unfollowupcounsellor) ? $counsellorlist[$unfollowupcounsellor->counsellor_id]['id'] : '';
        $unfollowupcounsellorname = !empty($unfollowupcounsellor) ? $counsellorlist[$unfollowupcounsellor->counsellor_id]['firstname'] : '';
        
        //dd($unfollowupcounsellorname);
        
        /*MainController::deleteRequiredFollowup($id, $formdetails->status);

        
        $reentry = Followup::where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc');

        $form = Reentry::where('form_id', '=', $id)
                    ->orderBy('updated_at', 'asc')
                    ->unionAll($reentry)
                    ->get();
        

        $followup = Followup::where('form_id', '=', $id)
                        ->Where('message_type', '=', '')
                        ->orderBy('comment', 'asc')
                        ->first();


        $checkuserassigned = Followup::where('form_id', '=', $id)
                                ->Where('counsellor_id', '=', $user->id)
                                ->count();

        //$a = DB::select("SELECT ( SELECT COUNT(id) FROM ".followuptablename(1)." WHERE 'counsellor_id' = $user->id) as a, (SELECT COUNT(*) FROM ".followuptablename(2).") as b, (SELECT COUNT(*) FROM ".followuptablename(3).") as c, (SELECT COUNT(*) FROM ".followuptablename(4).") as d, (SELECT COUNT(*) FROM ".followuptablename(5).") as e");
            //->Where('counsellor_id', '=', $user->id);
        dd("SELECT ( SELECT COUNT(id) FROM ".followuptablename(1)." WHERE counsellor_id = $user->id) as a, (SELECT COUNT(*) FROM ".followuptablename(2).") as b, (SELECT COUNT(*) FROM ".followuptablename(3).") as c, (SELECT COUNT(*) FROM ".followuptablename(4).") as d, (SELECT COUNT(*) FROM ".followuptablename(5).") as e");
        dd($checkuserassigned);
        
        $formemail = $formdetails->email;
        $formphone = $formdetails->phone;

        $admissionformcheck = Admissionform::where('email', '=', $formemail)
                                ->orWhere('phone', '=', $formphone)
                                ->first();
        $options = Option::get();
        $gdpi = array();

        foreach ($options as $option) {
            switch ($option->name) {
                case "GDPI_date":
                    $gdpi['date'] = $option->value;
                case "GDPI_location":
                    $gdpi['location'] = $option->value;
                case "GDPI_time":
                    $gdpi['time'] = $option->value;
                case "GDPI_contact":
                    $gdpi['phone'] = $option->value;
                default:

            }
        }

        //Check Previous Conversation
        $previousform = MyForm::select('id', 'status')
                                ->where('email' , $formemail)
                                ->where('dupcheck', 0)
                                ->where('id', '!=' , $id)
                                ->first();
        if(!empty($previousform)) {
            $previousformfollowups = Followup::where('form_id', '=', $previousform->id)
                    ->orderBy('updated_at', 'asc')
                    ->get();
        } else {
            $previousformfollowups = null;
        }

        $counsellorlist = getCounsellor();
        $unfollowupcounsellor = Followup::where('form_id', '=', $id)
                            ->Where('comment', '=', '')
                            ->Where('message_type', '=', '')
                            ->orderBy('id', 'desc')
                            ->first();

        $unfollowupcounsellorid = !empty($unfollowupcounsellor) ? $counsellorlist[$unfollowupcounsellor->counsellor_id]['id'] : '';
        $unfollowupcounsellorname = !empty($unfollowupcounsellor) ? $counsellorlist[$unfollowupcounsellor->counsellor_id]['firstname'] : '';

        return view('followupdetail',compact('request','user', 'id', 'formdetails','form','followup','admissionformcheck','checkuserassigned','gdpi', 'previousform', 'previousformfollowups', 'unfollowupcounsellorid', 'unfollowupcounsellorname'));*/

        return view('followupdetail',compact('request','user', 'id', 'formdetails','followupnum','admissionformcheck','checkuserassigned','followupnum','currentfollowup','previousformfollowups','reentrydata','hotlead_conversations', 'unfollowupcounsellorid', 'unfollowupcounsellorname'));
    }

    public function myoperatorCallDirect(Request $request)
    {
        myOperatorDirectCall($request->phone, $request->useroperatorid, $request->randomrefid);
        
    }

    public function followupSubmit(Request $request)     //Analyse Records
    {
        
        $user = Auth::user();
        $followupnum = $request->followupnum;
        
        $currentfollowup = followuptable($followupnum)->where('form_id', '=', $request->formid)
                    ->Where('counsellor_id', '=', $user->id)
                    ->Where('message_type', '=', "")
                    ->orderBy('id', 'desc')
                    ->first();

        //Delete All Recall Entry with that form
        Recall::where('form_id', '=', $request->formid)
                    ->delete();

        //Insert Reentry in Reentry Table

        if(!empty($currentfollowup->comment)) {

            $reentry = new Reentry;
            $reentry->counsellor_id = $currentfollowup->counsellor_id;
            $reentry->form_id       = $currentfollowup->form_id;
            $reentry->category      = $currentfollowup->category;
            $reentry->comment       = $currentfollowup->comment;
            $reentry->level         = $currentfollowup->level;
            $reentry->status        = 1;
            $reentry->message_type  = $currentfollowup->message_type;
            $reentry->created_at  = $currentfollowup->created_at;
            $reentry->updated_at  = $currentfollowup->updated_at;
            $reentry->save();
        }
        
        
        /*if($request->status == 'openandreserve' AND ($request->category == 'Call Not Picked' OR $request->category == 'Call Disconnected' OR $request->category == 'Not Reachable' OR $request->category == 'Switch Off' OR $request->category == 'Callback' OR $request->category == 'Concerned Person Not Available')) {*/
        if($request->status == 'openandreserve') {
            if(empty($request->calldate)) {
                //If Call Back Date Not Defined
                Session::flash('sweeterrorMessage', 'Call Back Time Not Provided');
                return redirect()->back();
            }
            $callbackdate = date('Y-m-d', strtotime($request->calldate));


            followuptable($followupnum)->where('id', $currentfollowup->id)
                ->update(['category' => $request->category,'comment' => $request->comment, 'status' => 2, 'updated_at' => Carbon::now()]);

            //Insert Time Into Recall
            $recall = new Recall;
            $recall->counsellor_id   = $currentfollowup->counsellor_id;
            $recall->form_id  = $currentfollowup->form_id;
            $recall->calldate  = $callbackdate;
            $recall->save();
            
            //Session::flash('errorMessage', 'Form Already Exist.');

            //$request->status = 'open'; //reset value (Used for trigger)

            /*Followup::where('id', $form->id)
            ->update(['category' => $request->category,'comment' => $request->comment, 'status' => 2]);*/

            
        }
        else {
            followuptable($followupnum)->where('id', $currentfollowup->id)
            ->update(['category' => $request->category,'comment' => $request->comment, 'status' => 1, 'updated_at' => Carbon::now()]);
        }
        //Update Form Status
        
        $formid = $currentfollowup->form_id;

        MyForm::where('id', $formid)
            ->update(['status' => $request->status]);

        $queryform = MyForm::where('id', $formid)->first();

        //Add Eail to Welcome Mailer
        $value = $currentfollowup->category;

        if($value == 'Interested for Admission' OR $value == 'Need More Time' OR $value == 'Form Filled' OR $value == 'Will Check after Nov CAT' OR $value == 'Will Check after Dec MAT' OR $value == 'Will Check after Jan CMAT' OR $value == 'Will Check after Feb MAT' OR $value == 'Will Check after May MAT' OR $value == 'Will Check after XAT') {
            
            //MainController::addEmailToWelcomeMailer($queryform->email);
        }
        
        

        
        Session::flash('successMessage', 'Data Successfully Updated');
        return redirect()->back();
    }

    public function followupListRecall(Request $request) {
        $user        = Auth::user();
        $counsellors = getCounsellor();
        $currentdate = date('Y-m-d');
        //$startdate = '2020-03-07';
        //$startdate   = date('Y-m-d', strtotime('-2 days'));
        //$enddate     = date('Y-m-d');

        $list = MyForm::select('recalls.calldate', 'forms.id as formid', 'followcounts', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->leftJoin('recalls', 'forms.id', '=', 'recalls.form_id')
                    ->where('forms.status','openandreserve')
                    ->where('recalls.counsellor_id',$user->id)
                    ->where('recalls.calldate' ,'<=', $currentdate)
                    ->get();
                    
        

        //Recall By Date Provided
        /*$listbydate = Followup::select('followups.*','recalls.calldate', 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                ->join('forms', 'forms.id', '=', 'followups.form_id')
                ->rightJoin('recalls', 'forms.id', '=', 'recalls.form_id')
                ->where('followups.counsellor_id',$user->id)
                ->where('followups.status',2)
                ->where('recalls.calldate' ,'=', $currentdate)
                ->where('forms.status','openandreserve')
                ->when($search, function ($listbydate) use ($search,$category) {
                    if($category == 'level') {
                        $search = followuptexttoCount($search);
                    } elseif($category == 'posted_at') {
                        return $listbydate->whereDate($category, $search);        
                    }
                    return $listbydate->where("forms.".$category,'like', '%'.$search.'%');      
                    
                });
        if(!empty($request->direction) AND !empty($request->sort)) {
            $listbydate = $listbydate->orderBy($request->sort, $request->direction);
        }
        $listbydate = $listbydate->paginate(20,$page);*/

        /*$followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        if(!empty($request->startdate)) {
            $startdate = date('Y-m-d',strtotime($request->startdate));
            $enddate   = date('Y-m-d',strtotime($request->enddate));

        } else {
            $startdate = date('Y-m-d');
            $enddate = date('Y-m-d');
        }

        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where('counsellor_id',$user->id)
                    ->where('comment', '!=', '')
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->orderBy('updated_at', 'asc')
                    ->get();*/
        
        
        return view('recalllist',compact('request','user','list', 'counsellors'));
    }

    public function callLogs(Request $request) {
        $user        = Auth::user();
        $counsellors = getCounsellor();
        $category    = $request->category;
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        if(!empty($request->startdate)) {
            $startdate = date('Y-m-d',strtotime($request->startdate));
            $enddate   = date('Y-m-d',strtotime($request->enddate));

        } else {
            $startdate = date('Y-m-d');
            $enddate = date('Y-m-d');
        }

        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where('counsellor_id',$user->id)
                    ->where('comment', '!=', '')
                    ->whereBetween(DB::raw("Date($tablename.updated_at)"), [$startdate, $enddate])
                    ->orderBy('updated_at', 'asc')
                    ->get();
                    
        return view('calllogs',compact('request','user','list', 'counsellors','followupnum'));

    }


    public function updateStudentDetailSubmit(Request $request) {

        $user = Auth::user();
        MyForm::where('id', $request->formid)
                        ->update(['name' => $request->name, 'email' => $request->email]); 

        Session::flash('successMessage', 'Data Successfully Updated');
        return redirect()->back();
    }

    public function followupListAll(Request $request)     //Followup Complete List(Pending and Complete)
    {
        $user = Auth::user();
        $counsellors = getCounsellor();
        $followupnum = $request->followupnum;
        $category    = $request->category;
        $tablename = followuptablename($followupnum);
        
        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where('counsellor_id',$user->id)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get();
                    
        return view('followuplist',compact('request','user','list','counsellors', 'followupnum'));

    }


    public function analyseRecord(Request $request)     //Analyse Records
    {
        $user = Auth::user();
        return view('bulkform',compact('request','user'));
    }

    public function counsellorFollowupList(Request $request,$id,$name, $followupnum) {
        $user = Auth::user();
        $counsellors = getCounsellor();

        $tablename = followuptablename($followupnum);

        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where('counsellor_id',$id)
                    ->where("$tablename.status",0)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('counsellorwisefollowuplist',compact('request','user','list', 'counsellors','id','name', 'followupnum'));
    }

    public function counsellorTransferCallsSubmit(Request $request)
    {
        $user = Auth::user();
        $forms = json_decode($request->forms); //Here Forms variable contain followups id list
        $current_time = Carbon::now();

        $newcounsellor = $request->counsellor;
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);
        
        $data = array();

        followuptable($followupnum)->whereIn('id', $forms)
            ->update(['counsellor_id' => $newcounsellor]);

        Session::flash('successMessage', 'Data Successfully Transferred');
        return redirect()->back();
    }



    /*Others*/

    public function sendSmsSubmit(Request $request)     //Analyse Records
    {
        $user = Auth::user();

        /*$form = Followup::where('id', '=', $request->id)
                ->first();*/

        
        $receiver = MyForm::where('id' ,'=', $request->receiverid)
                                ->first();
        text_local_api($receiver->phone,$request->message);
        
        $sms = new Sms;
        $sms->userid      = $user->id;
        $sms->receiver_id = $request->receiverid;
        $sms->followup_id = $request->id;
        $sms->message     = $request->message;
        $sms->save();


        $followup = new Reentry;
        $followup->counsellor_id = $request->counsellor_id;
        $followup->form_id       = $request->receiverid;
        $followup->comment       = $request->message;
        $followup->level         = $request->followupnum;
        $followup->status        = 1;
        $followup->message_type  = 'sms';
        $followup->reentry       = 1;
        $followup->save();

        Session::flash('successMessage', 'Message Send Successfully');
        return redirect()->back();
    }


    /*Reports*/
    public function downloadCompleteExcel(Request $request)
    {
        $mytime     = Carbon::now();
        return Excel::download(new CompleteFormsExport, 'complete-forms-excel-'.$mytime.'.csv');
    }

    public function downloadUniqueExcel(Request $request)
    {
        $mytime     = Carbon::now();
        return Excel::download(new UniqueFormsExport, 'unique-forms-excel-'.$mytime.'.csv');
    }


    public function downloadCompleteWithRemarksExcel(Request $request)
    {
        $user       = Auth::user();
        $mytime     = Carbon::now();

        return Excel::download(new RemarksReportExport, 'remarks-report-excel-'.$mytime.'.csv');
    }

    public function admissionForm(Request $request)      //View Forms
    {
        $user = Auth::user();
        
        $myform = Admissionform::orderBy('date', 'asc')
                    ->get();
        
        return view('admissionforms',compact('request','user','myform'));
    }

    public function checkAdmissionForm(Request $request)      //View Forms
    {
        $user = Auth::user();
        
        $myform = Admissionform::where('regid', $request->regid)
                    ->first();
        
        if(empty($myform)) {
            return 'false';
        }
        $admission = Admission::where('regid', $request->regid)
                        ->first();
        if(!empty($admission)) {
            return 'exist';
        }

        $myform->gdpidate = date('d-m-Y', strtotime($myform->gd_pi_date));

        return $myform;
    }

    public function admissionsList(Request $request)
    {
        $user = Auth::user();
        $admission = Admission::get();
        return view('admin.admissionlist',compact('request','user','admission'));
    }

    public function getadmissionsData(Request $request)      //View Forms
    {
        $user = Auth::user();
        
        $admission = Admission::where('regid', $request->regid)
                        ->first();

        $admission->admissiondate = date('d-m-Y', strtotime($admission->admission_date));
        $admission->gdpidate = date('d-m-Y', strtotime($admission->gdpi_date));
        
        return $admission;
    }

    public function admissionsSubmit(Request $request)
    {
        $user = Auth::user();
        $admissiondate = date('Y-m-d 00:00:00', strtotime($request->admission_date));
        $gdpi_date = date('Y-m-d 00:00:00', strtotime($request->gdpi_date));
        $admission = Admission::where('regid', '=', $request->reg_id)
                            ->first();


        if(empty($admission)) {
            $admission = new Admission;
            $admission->regid          = $request->reg_id;
            $admission->name           = $request->student_name;
            $admission->course         = $request->course;
            $admission->fee_last_date  = $request->fee_last_date;
            $admission->amount_paid    = $request->amount_paid;
            $admission->balance_amount = $request->balance_amount;
            $admission->xdate          = $request->xdate;
            $admission->hostel_fee     = $request->hostel_fee;
            $admission->admission_date = $admissiondate;
            $admission->gdpi_date      = $gdpi_date;
            $admission->save();
        
        } else {
            Admission::where('regid', '=', $request->reg_id)
                ->update(['course' => $request->course, 'fee_last_date' => $request->fee_last_date, 'amount_paid' => $request->amount_paid, 'balance_amount' => $request->balance_amount, 'xdate' => $request->xdate, 'hostel_fee' => $request->hostel_fee, 'admission_date' => $admissiondate ]);
        }
        Session::flash('successMessage', 'Data Successfully Updated');
        return redirect()->back();
    }

    public function checkEmailExist(Request $request) 
    {
        $user = Auth::user();
        $checkduplicate = MyForm::where('email', '=', $request->email)
                            ->orderBy('id', 'asc')
                            ->first();
        if(empty($checkduplicate)) {
            return 'false';
        } else {
            return $checkduplicate->id;
        }
    }

    public function closedCalls(Request $request)      //View Forms
    {
        $user = Auth::user();

        //DB::update("UPDATE forms LEFT JOIN ( SELECT id,COUNT(id) AS counter FROM forms GROUP BY email ) dup ON forms.id = dup.id SET `dupcheck`=1  WHERE counter IS NULL");

        $search = $request->search;
        $followupnum = $request->followupnum;
        $tablename = followuptablename($followupnum);

        $categories = followuptable($followupnum)->select('category')
                    ->distinct()
                    ->get();


        $remarks = $request->remarks;


        $followuplist = followuptable($followupnum)->select('forms.id','forms.name','forms.email','forms.phone','forms.city','forms.source','forms.course','forms.query','forms.followcounts','forms.posted_at',"$tablename.reentry","$tablename.counsellor_id","$tablename.category","$tablename.status as followstatus","$tablename.updated_at as followdate", DB::raw('count(forms.email) as total'))
            ->join('forms', 'forms.id', '=', "$tablename.form_id")
            ->where('followcounts', '=', $followupnum)
            ->where('dupcheck', '=', 0)
            ->where(function($query) use($user) {
                $query->where('forms.status', '=', 'closed');
                $query->orWhere('forms.status', '=', 'complete');
            })
            ->when($search, function ($myform) use ($search, $followupnum) {
                if($search != 'All' AND $followupnum > 1) {
                    return $myform->where('category', $search);    
                }
                
            })
            ->groupBy('email')
            ->orderBy('posted_at', 'desc')
            ->get();  

        $counsellors = User::get()->keyBy('id')->toArray();


        return view('closedcalls',compact('request','user','followuplist','counsellors', 'categories', 'search', 'followupnum'));
    }

    public function openAndAssignCallsSubmit(Request $request)     
    {
        $user = Auth::user();
        $forms = json_decode($request->forms);
        $current_time = Carbon::now();
        
        $data = array();
        $data1 = array();
        foreach($forms as $form) {
            $lastlevel = $request->level - 1;
            array_push($data1,array('counsellor_id'=> $user->id, 'form_id'=> $form, 'level'=> $lastlevel, 'status'=> 1, 'reentry'=> 1, 'comment'=>'Query '.$request->status.' By Admin('.$user->firstname.')', 'created_at' => $current_time, 'updated_at' => $current_time));

            array_push($data,array('counsellor_id'=> $request->counsellor, 'form_id'=> $form, 'level'=> $request->level, 'status'=> 0, 'reentry'=> 0, 'comment'=>'', 'created_at' => $current_time, 'updated_at' => $current_time));

        }

        Reentry::insert($data1);

        followuptable($request->level)->insert($data);

        MyForm::whereIn('id', $forms)
            ->update(['status' => $request->status]);

        MyForm::whereIn('id', $forms)->increment('followcounts');

        Session::flash('successMessage', 'Data Successfully Assigned');
        return redirect()->back();
    }
	
    /*Mini Functions*/

    public function deleteRequiredFollowup($id, $status)     
    {
        //Delete Followups with status 0 if form closed
        if($status == 'closed' OR $status == 'complete')
        {
            Followup::where('form_id', $id)->Where('status', 0)->delete();
        }

        //Delete all followups with status 0 except last
        $missed = Followup::where('form_id', '=', $id)
                    ->where('status', 0)
                    ->orderBy('updated_at', 'asc')
                    ->get();

        $totalmissed = $missed->count();
        if($totalmissed >= 2) {
            $deletemissed = $missed->count() - 1;
            Followup::where('form_id', $id)->Where('status', 0)->take($deletemissed)->delete();
            
        }        
    }

    public function addEmailToWelcomeMailer($email)
    {
        
        if (WelcomeMailer::where('email', '=', $email)->exists() == false) {
            
            $getUser = MyForm::where('email', '=', $email)->Where('dupcheck', 0)->first();
            $welcomemailer = new WelcomeMailer;
            $welcomemailer->form_id  = $getUser->id;
            $welcomemailer->Email   = $getUser->email;
            $welcomemailer->name    = $getUser->name;
            $welcomemailer->status  = 0;
            $welcomemailer->save();

        }
    }

    public function testManish() {
        //text_local_api("9999393712", "Dear Student, Welcome Manish");
        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $name = generateRandomString();

        DB::table('testme')->insert([
            'name' => $name
        ]);
        return "Send";
    }

    public function queryrectifyduplicate()
    {
        $getData = MyForm::select('email',DB::raw('count(email) as duplicatecount'))
                    ->where('followcounts', '>', 0)
                    ->Where('status', 'open')
                    ->groupBy('email')
                    ->havingRaw('COUNT(email) > 1')
                    ->orderBy('duplicatecount', 'desc')
                    ->get();

        $datatoactivate = array();
        $emailtodeactivate = array();

        foreach($getData as $data) {
            $email = $data->email;

            $list = MyForm::where('email', '=', $email)
                        ->orderBy('id', 'asc')
                        ->get();

            foreach($list as $form) {
                if($form->followcounts > 0 AND $form->status == 'open') {
                    array_push($datatoactivate, $form->id);
                    array_push($emailtodeactivate, $form->email);
                    
                    break;
                }
                //dd($form);
            }
            //dd($list);
        }

        MyForm::whereIn('email',$emailtodeactivate)
            ->update(['dupcheck' => 1]);

        MyForm::whereIn('id',$datatoactivate)
            ->update(['dupcheck' => 0]);


        dd('end');

    }

    public function queryrectifyduplicatesteptwo(Request $request) {
        $followupnum = $request->followupnum;

        $tablename = followuptablename($followupnum);

        $datatoupdate = array();

        $list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'forms.dupcheck as form_dupcheck', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    //->where('counsellor_id',14)
                    ->where('forms.dupcheck',1)
                    ->where("$tablename.status",0)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get();


        foreach($list as $data) {
            array_push($datatoupdate, $data->id);
        }
        //dd($datatoupdate);
        followuptable($followupnum)->whereIn('id',$datatoupdate)
            ->update(['comment' => 'Query Closed By Admin(Manish)', 'status' => 1]);
        
        //dd($datatoupdate);
        dd('end');
    }

    public function queryrectifyupdatealldup(Request $request)
    {

        $dupemails = DB::table('record')->limit(100)->get();

        foreach($dupemails as $list) {

            $form = MyForm::where('email', $list->email)
                        ->orderBy('id', 'asc')
                        ->first();

            if($form) {
                MyForm::where('id',$form->id)
                    ->update(['dupcheck' => 0]);
                //dd($form);    
            }
            
            //dd($list->email);
        }

        DB::table('record')->limit(100)->delete();
        dd('end');
        //dd($dupemails);
    }
}