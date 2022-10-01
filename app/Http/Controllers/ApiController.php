<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Model\User;
use App\Model\Role;
use App\Model\MyForm;
use App\Model\Collegedekhoforms;
use App\Model\Admissionform;
use App\Model\ShikshaCafForm;
use App\Model\ShikshaCafFormChild1;
use App\Model\ShikshaCafFormChild2;
use Carbon\Carbon;

use Auth;

use Illuminate\Support\Facades\Input;
//use App\Traits\ZohoInsertQueryTrait;
use App\Traits\AssignQueryTrait;

class ApiController extends Controller
{
	use AssignQueryTrait;
    public $restful = true;

    public function store(Request $request)		//Add New Form Manually
	{
		/*$page = json_encode(array('helo','tfd'));
		return Response::json(array(
            'error' => false,
            'pages' => $page),
            200
        );*/
        if($request->name != "" AND $request->email != "" AND $request->phone != "" AND $request->city != "" AND $request->course != "" AND $request->source != "" AND $request->message != "") {
	    $myform = new MyForm;
	    $myform->name   = $request->name;
	    $myform->email  = $request->email;
	    $myform->phone  = $request->phone;
	    $myform->city   = $request->city;
	    $myform->course = $request->course;
	    $myform->source = $request->source;
	    $myform->query  = $request->message;
	    $myform->save();
	    return "success";
	    }
	    else {
	      return "failed";
	    }
	}

	public function getLastInsertedId(Request $request) 
	{
		$lastformid = MyForm::orderBy('id', 'desc')
						->first();

		return $lastformid->id;
	}

	public function verificationMailHitter(Request $request)
	{
		$lastinsertedidbeforehit = $request->lastinsertedidbeforehit;
		$remainingForms = MyForm::where('id', '>', $lastinsertedidbeforehit)
							->orderBy('id', 'asc')
							->get();

		foreach ($remainingForms as $key => $form) {
			
			addVerificationMail($form->id, $form->email);
		}
		return "Success";
	}

	public function getImidiateData(Request $request)
	{
		ini_set("allow_url_fopen", 1);
		$current_time = Carbon::now();

		$myform = new MyForm;
		$myform->sourcesno = $request->sno;
		$myform->qid = $request->Qid;
		$myform->name = $request->Name;
		$myform->email = $request->Email;
		$myform->phone = $request->Mobile;
		$myform->city = $request->City;
		$myform->course = $request->Qualification;
		$myform->source = $request->source;
		$myform->status = 'open';
		$myform->posted_at = dateToTimestamp($request->Date);
		$myform->query = $request->Query;
		$myform->created_at = $current_time;
		$myform->updated_at = $current_time;
		$myform->save();

		$lastid = $myform->id;

		$this->allotFirstQueryToCounsellor($request->name, $request->email, $request->phone, $request->source, $lastid);

		if($myform->course != 'BBA' && $myform->course != 'BCA' && $myform->course != 'MCA'&& $myform->course != 'B.A Economics(H)') {
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);
			sendtoEmailAutomation($request->Email, $request->source);	
		}
		

		return "Success";
	}

	public function getImidiateApplicationform(Request $request)
	{
		ini_set("allow_url_fopen", 1);
		$current_time = Carbon::now();

		//dd(json_decode($request->data));
		$obj = json_decode($request->data);
		$data = array();

		$current_time = Carbon::now();
		
		//return $current_time;
		
		foreach ($obj as $object) {
			//$object = (object) $object;

			//dd($object);

			$date = date('Y-m-d 00:00:00',strtotime(str_replace('/', '-', $object->Date)));
			$gdpi_date = !empty($object->GD_PI_Date) ? date('Y-m-d 00:00:00',strtotime(str_replace('/', '-', $object->GD_PI_Date))) : NULL;
			
			$new = array('sourcesno'=>$object->sno,'regid'=>$object->Regid,'formid'=>$object->Formid,'programme_af'=>$object->Programme_AF,'qualifying_exam'=>$object->Qualifying_exam,'qualifying_exam2'=>$object->Qualifying_exam2,'qualifying_exam3'=>$object->Qualifying_exam3,'exam_date'=>$object->Exam_Date,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'place' => $object->Place,'date' => $date,'payment_mode' => $object->Payment_Mode,'gd_pi_date' => $gdpi_date ,'gd_pi_center' => $object->GD_PI_Center ,'form_status' =>$object->Form_Status, 'gdpi_result' => $object->GDPI_Result, 'onlinepayment_status' => $object->OnlinePayment_Status);
			array_push($data, $new);
			//dd($object->Date);

			sendtoEmailAfterFormFillAutomation($object->Email, "afterformfill");

		}

		//Admissionform::insert($data); // Eloquent approach
		return "Success";
	}

	public function getWebsiteHome(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'Website Home Page')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);
		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-Website-Home.aspx?qid=294745');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-Website-Home.aspx?qid='.$lastformid->sourcesno);
		}

		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);

		
		
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;

			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'Website Home Page','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'Website Home Page');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}

	public function getWebsiteInnerPage(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'Website Inner Page')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-InnerQuery.aspx?qid=299970');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-InnerQuery.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($json);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;

			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'Website Inner Page','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'pagepath' => $object->PagePath, 'created_at' => $current_time,  'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'Website Inner Page');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}


	public function getGoogleCampaign(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'Google')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-Google.aspx?qid=195835');
		} else {
			$json = fetchSslApi('http://jimsindia.org/apis/Response-Google.aspx?qid='.$lastformid->sourcesno);
		}

		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'Google','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'Google');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}


	public function getFacebookCampaign(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'Facebook')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		
		if($lastformid == NULL) {
			$json = fetchSslApi('http://jimsindia.org/apis/Response-FB.aspx?qid=195835');
		} else {
			$json = fetchSslApi('http://jimsindia.org/apis/Response-FB.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'Facebook','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'Facebook');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}


	public function getMBAUniverse(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'JIMS MBA Universe')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		
		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-MBAUniverse.aspx?qid=72086');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-MBAUniverse.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'JIMS MBA Universe','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'JIMS MBA Universe');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}

	public function getCollegeDuniaLandingPage(Request $request)		//Add New Form From Inhouse Tracking of College Dunia Landing Page
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'JIMS CD Landing Page')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		
		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-CollegeDunia.aspx?qid=195835');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-CollegeDunia.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'JIMS CD Landing Page','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'JIMS CD Landing Page');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}


	public function getIPU(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'JIMS IPU')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		
		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-IPU.aspx?qid=189784');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-IPU.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'JIMS IPU','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'JIMS IPU');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}

	public function getMockTest(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*Get Last Inserted Id of Data*/
		$lastformid = MyForm::where('source', 'JIMS Mock Test')
						->whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();
		//dd($lastformid->sourcesno);

		
		if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-Mock.aspx?qid=1');
		} else {
			$json = fetchSslApi('https://jimsindia.org/apis/Response-Mock.aspx?qid='.$lastformid->sourcesno);
		}
		
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		
		$obj = json_clean_decode($json);
		$data = array();
		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;
			$new = array('sourcesno'=>$object->sno,'qid'=>$object->Qid,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'city' => $object->City,'course' => $object->Qualification,'source' => 'JIMS Mock Test','query' => $object->Query ,'status' => 'open','posted_at' => dateToTimestamp($object->Date), 'created_at' => $current_time, 'updated_at' => $current_time);
			array_push($data, $new);
			//dd($object->Date);

			//$this->insertZohoQuery($object->Name, $object->Email, $object->Mobile, 'JIMS Mock Test');

		}

		MyForm::insert($data); // Eloquent approach
		return "Success";
	}


	public function getAdmissionForm(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		//Delete All Query
		$deletentry = Admissionform::truncate();

		/*Get Last Inserted Id of Data*/
		/*$lastformid = Admissionform::whereNotNull('sourcesno')
						->orderBy('id', 'desc')
						->first();*/
		//dd($lastformid->sourcesno);

		
		/*if($lastformid == NULL) {
			$json = fetchSslApi('https://jimsindia.org/apis/AdmissionForm.aspx?qid=138220');
		} else {
			var_dump($lastformid->sourcesno);
			$json = fetchSslApi('https://jimsindia.org/apis/AdmissionForm.aspx?qid='.$lastformid->sourcesno);
		}*/
		//$json = fetchSslApi('https://jimsindia.org/response.aspx?qid=51452');
		$json = fetchSslApi('https://jimsindia.org/apis/AdmissionForm.aspx?qid=304828');
		$obj = json_clean_decode($json);
		$data = array();

		$current_time = Carbon::now();
		//dd($obj);
		//return $current_time;
		foreach ($obj as $object) {
			$object = (object) $object;

			$date = date('Y-m-d 00:00:00',strtotime(str_replace('/', '-', $object->Date)));
			$gdpi_date = !empty($object->GD_PI_Date) ? date('Y-m-d 00:00:00',strtotime(str_replace('/', '-', $object->GD_PI_Date))) : NULL;
			
			$new = array('sourcesno'=>$object->sno,'regid'=>$object->Regid,'formid'=>$object->Formid,'programme_af'=>$object->Programme_AF,'qualifying_exam'=>$object->Qualifying_exam,'qualifying_exam2'=>$object->Qualifying_exam2,'qualifying_exam3'=>$object->Qualifying_exam3,'exam_date'=>$object->Exam_Date,'name'=>$object->Name, 'email'=> $object->Email,'phone' => $object->Mobile,'place' => $object->Place,'date' => $date,'payment_mode' => $object->Payment_Mode,'gd_pi_date' => $gdpi_date ,'gd_pi_center' => $object->GD_PI_Center ,'form_status' =>$object->Form_Status, 'gdpi_result' => $object->GDPI_Result, 'onlinepayment_status' => $object->OnlinePayment_Status);
			array_push($data, $new);
			//dd($object->Date);

		}

		Admissionform::insert($data); // Eloquent approach
		return "Success";
	}

	public function getShikshaLeads(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();

			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst($request->source);
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		   	//$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return "Success";

		} else {
			return "Invalid Api Key";
		}
		
		//return "Success";
	}

	public function getShikshaMatchedLeads(Request $request)		//Add New Form From Shiksha Matched Response
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();


			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = 'Shiksha Matched Response';
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		    //$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return "Success";

		} else {
			return "Invalid Api Key";
		}
		
		//return "Success";
	}

	public function getCareersLeads(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();


			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('Career360');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		    //$this->insertZohoQuery($request->name, $request->email, $request->phone, ucfirst('Career360'));

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return json_encode("Success");

		} else {
			return json_encode("Invalid Api Key");
		}
		
		//return "Success";
	}


	public function getCollegeDekhoLeads(Request $request)		//Add New Form From Inhouse Tracking of MBA Universe
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			//check if email or phone exist
			$check = MyForm::where('email', $request->email)
						->orWhere('phone', $request->phone)
						->first();

			$current_time = Carbon::now();


			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('CollegeDekho');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		    //$this->insertZohoQuery($request->name, $request->email, $request->phone, ucfirst('CollegeDekho'));

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

			if(!empty($check)) {
				return json_encode("Email / Phone No. Already Exist");
			}

		    return json_encode("Success");

		} else {
			return json_encode("Invalid Api Key");
		}
		
		//return "Success";
	}


	public function getCollegeDuniaLeads(Request $request)		//Add New Form From College Dunai
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();

			
			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			
			$source = $request->source;

			if(!empty($dateon = $request->dateon))
			{
				$dateon = $request->dateon;
				$timeon = date("H:i:s", strtotime($request->timeon));
				$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));
			} else {
				$generated_on = Carbon::now();
			}
			

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('CollegeDunia');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		    //$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return json_encode("Success");

		} else {
			return json_encode("Invalid Api Key");
		}
		
		//return "Success";
	}

	public function getCareerLauncherLeads(Request $request) 
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();

			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('CareerLauncher');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		   	//$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return "Success";

		} else {
			return "Invalid Api Key";
		}
		
		//return "Success";
	}

	public function getShikshaCafForms(Request $request)
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();			

			$myform = new ShikshaCafForm;
			$myform->course_applying_for  = $request->course_applying_for;
			$myform->first_name  = $request->first_name;
			$myform->last_name  = $request->last_name;
			$myform->email  = $request->email;
			$myform->alternate_email  = $request->alternate_email;
			$myform->mobile_number  = $request->mobile_number;
			$myform->alternate_mobile  = $request->alternate_mobile;
			$myform->dob  = $request->dob;
			$myform->gender  = $request->gender;
			$myform->category  = $request->category;
			$myform->subcategory  = $request->subcategory;
			$myform->nationality  = $request->nationality;
			$myform->blood_group  = $request->blood_group;
			$myform->mother_tongue  = $request->mother_tongue;
			$myform->aadhaar_number  = $request->aadhaar_number;
			$myform->maritial_status  = $request->maritial_status;
			$myform->passport_photo  = $request->passport_photo;		
			
			$myform->declaration  = $request->declaration;
			$myform->photo_upload  = $request->photo_upload;
			$myform->upload_graduation_grade  = $request->upload_graduation_grade;
		    $myform->save();

		    $lastid = $myform->id;

		    $myform_child1 = new ShikshaCafFormChild1;
			$myform_child1->cafid  = $lastid;
			$myform_child1->permanent_address_line_1  = $request->permanent_address_line_1;
			$myform_child1->permanent_address_line_2  = $request->permanent_address_line_2;
			$myform_child1->permanent_city  = $request->permanent_city;
			$myform_child1->permanent_state  = $request->permanent_state;
			$myform_child1->permanent_country  = $request->permanent_country;
			$myform_child1->permanent_pin_code  = $request->permanent_pin_code;
			$myform_child1->correspondence_same_as_permanent  = $request->correspondence_same_as_permanent;
			$myform_child1->correspondence_address_line_1  = $request->correspondence_address_line_1;
			$myform_child1->correspondence_address_line_2  = $request->correspondence_address_line_2;
			$myform_child1->correspondence_city  = $request->correspondence_city;
			$myform_child1->correspondence_state  = $request->correspondence_state;
			$myform_child1->correspondence_country  = $request->correspondence_country;
			$myform_child1->correspondence_pin_code  = $request->correspondence_pin_code;
			$myform_child1->family_annual_income  = $request->family_annual_income;
			$myform_child1->father_name  = $request->father_name;
			$myform_child1->father_occupation  = $request->father_occupation;
			$myform_child1->mother_name  = $request->mother_name;
			$myform_child1->mother_occupation  = $request->mother_occupation;
		    $myform_child1->save();

		    $myform_child2 = new ShikshaCafFormChild2;
		    $myform_child1->cafid  = $lastid;
			$myform_child2->hostel_required  = $request->hostel_required;
			$myform_child2->tenth_school  = $request->tenth_school;
			$myform_child2->tenth_board  = $request->tenth_board;
			$myform_child2->tenth_passing_year  = $request->tenth_passing_year;
			$myform_child2->tenth_percentage  = $request->tenth_percentage;
			$myform_child2->tenth_marks_obtained  = $request->tenth_marks_obtained;
			$myform_child2->twelveth_school  = $request->twelveth_school;
			$myform_child2->twelveth_board  = $request->twelveth_board;
			$myform_child2->twelveth_passing_year  = $request->twelveth_passing_year;
			$myform_child2->twelveth_percentage  = $request->twelveth_percentage;
			$myform_child2->twelveth_marks_obtained  = $request->twelveth_marks_obtained;
			$myform_child2->graduation_status  = $request->graduation_status;
			$myform_child2->graduation_mode_of_studying  = $request->graduation_mode_of_studying;
			$myform_child2->graduation_institute_name  = $request->graduation_institute_name;
			$myform_child2->graduation_university  = $request->graduation_university;
			$myform_child2->graduation_passing_year  = $request->graduation_passing_year;
			$myform_child2->graduation_percentage  = $request->graduation_percentage;
			$myform_child2->graduation_marks_obtained  = $request->graduation_marks_obtained;
			$myform_child2->cat_status  = $request->cat_status;
			$myform_child2->cat_roll_number  = $request->cat_roll_number;
			$myform_child2->cat_score  = $request->cat_score;
			$myform_child2->cat_marks_obtained  = $request->cat_marks_obtained;
			$myform_child2->mat  = $request->mat;
			$myform_child2->gmat  = $request->gmat;
			$myform_child2->work_experience_company_name  = $request->work_experience_company_name;
			$myform_child2->work_experience_designation  = $request->work_experience_designation;
			$myform_child2->work_experience_location  = $request->work_experience_location;
			$myform_child2->work_experience_from_date  = $request->work_experience_from_date;
			$myform_child2->work_experience_to_date  = $request->work_experience_to_date;
			$myform_child2->work_experience_current_work_here  = $request->work_experience_current_work_here;
			$myform_child2->work_experience_roles  = $request->work_experience_roles;
		    $myform_child2->save();

		    return "Success";

		} else {
			return "Invalid Api Key";
		}
		
		//return "Success";
	}

	public function getFacebookWebhookForms(Request $request)
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			return "success";
		}
	}

	public function rectifyDate(Request $request)		//Add New Form From Jims Api (Landing Page)
	{
		ini_set("allow_url_fopen", 1);
		$user = Auth::user();

		/*For Website Home Page*/
		$json = fetchSslApi('https://jimsindia.org/apis/Response-Website-Home.aspx?qid=148184');
		$obj = json_clean_decode($json);
		foreach ($obj as $object) {
			$object = (object) $object;
			MyForm::where('sourcesno', $object->sno)
            ->update(['posted_at' => dateToTimestamp($object->Date)]);

		}

		/*For Website Home Page*/
		$json = fetchSslApi('https://jimsindia.org/apis/Response-InnerQuery.aspx?qid=154125');
		$obj = json_clean_decode($json);
		foreach ($obj as $object) {
			$object = (object) $object;
			MyForm::where('sourcesno', $object->sno)
            ->update(['posted_at' => dateToTimestamp($object->Date)]);

		}

		/*For Google*/
		$json = fetchSslApi('https://jimsindia.org/apis/Response-Google.aspx?qid=52611');
		$obj = json_clean_decode($json);
		foreach ($obj as $object) {
			$object = (object) $object;
			MyForm::where('sourcesno', $object->sno)
            ->update(['posted_at' => dateToTimestamp($object->Date)]);

		}
		var_dump('completed');

		
	}

	public function getKenytChatLeads(Request $request)		//Add New Form
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			//check if email or phone exist
			$check = MyForm::where('email', $request->email)
						->orWhere('phone', $request->phone)
						->first();

			$current_time = Carbon::now();


			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('Chatbot');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		    //$this->insertZohoQuery($request->name, $request->email, $request->phone, ucfirst('CollegeDekho'));

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

			if(!empty($check)) {
				return json_encode("Email / Phone No. Already Exist");
			}

		    return json_encode("Success");

		} else {
			return json_encode("Invalid Api Key");
		}
		
		//return "Success";
	}

	public function getMyUniLeads(Request $request)		//Add New Form From GetMyUniv
	{
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			$current_time = Carbon::now();

			$name   = $request->name;
			$email  = $request->email;
			$phone  = $request->phone;
			$city   = $request->city;
			$dateon = $request->dateon;
			$timeon = date("H:i:s", strtotime($request->timeon));
			$source = $request->source;

			$generated_on = date('Y-m-d H:i:s', strtotime($request->dateon." ".$request->timeon));

			$myform = new MyForm;
		    $myform->name   = $request->name;
		    $myform->email  = $request->email;
		    $myform->phone  = $request->phone;
		    $myform->city   = $request->city;
		    $myform->course = $request->course;
		    $myform->source = ucfirst('GetMyUniv');
		    $myform->query  = "";
		    $myform->status  = "open";
		    $myform->posted_at = $generated_on;
		    $myform->created_at = $current_time;
		    $myform->updated_at = $current_time;
		    $myform->save();

		   	//$this->insertZohoQuery($request->name, $request->email, $request->phone, $request->source);

		    $lastid = $myform->id;
			sendSingleSmtpHotLeadVerifyEmail($request->Email, $lastid, $request->Name);

			sendSingleSmtpHotLeadVerifyEmail($request->email, $lastid, $request->name);
			sendtoEmailAutomation($request->email, $request->source);

		    return "Success";

		} else {
			return "Invalid Api Key";
		}
		
		//return "Success";
	}

	public function getMyoperatorWebhookOncallForms(Request $request) {
		ini_set("allow_url_fopen", 1);
		if(!empty($request->authapikey) AND ($request->authapikey == "1edef3078a49b6c248c80fb6c5c9fb66f5375881"))
		{
			//	myoperator_test
			$data  = json_encode($_REQUEST);
			DB::table('myoperator_test')->insert(
			    ['rd' => $data]
			);
		} else {
			return json_encode("Invalid Api Key");
		}
	}



}
