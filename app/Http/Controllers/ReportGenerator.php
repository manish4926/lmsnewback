<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Model\MyForm;
use App\Model\Admissionform;
use App\Model\Formconversioreport;

class ReportGenerator extends Controller
{
    public function generateFormConversionReport(Request $request) {
    	$admissionforms = Admissionform::select('regid','name','email','programme_af','date')
							->where('programme_af' ,'!=', '---Fellowship Programme in Management-I')->get(); 
		$emailList = $admissionforms->pluck('email')->toArray();

		$getAllForms = MyForm::select('id','email','source')
							->whereIn('email', $emailList)
							->orderBy('source', 'asc')
							->get()->toArray();

		unset($emailList);

		$admissionforms = $admissionforms->toArray();
		foreach ($admissionforms as $key => $admissionform) {
			$x = searchArrayMultidimentionFollowups($admissionform['email'],'email', $getAllForms);

			//var_dump($x);
			if($x == NULL) {
				$admissionforms[$key]['firstsource'] = "NA"; 
				$admissionforms[$key]['allsources'] = "NA"; 
			} else {
				$firstsource = $getAllForms[$x[0]]['source'];

				$allsources = array();
				foreach ($x as $xkey => $xvalue) {
					array_push($allsources, $getAllForms[$xvalue]['source']);
				}
				$allsources = implode(",",$allsources);

				$admissionforms[$key]['firstsource'] = $firstsource; 
				$admissionforms[$key]['allsources'] = $allsources; 
			}
		}

		Formconversioreport::truncate();
		Formconversioreport::insert($admissionforms);
		return "Success";
    }

    /*public function generateRemarksReport(Request $request) 
    {
    	$followupnum = 1;
    	$tablename = followuptablename($followupnum);
    	$counsellorlist = getCounsellor();

    	$list = followuptable($followupnum)->select("$tablename.*", 'forms.id as formid','level', 'name', 'forms.email as form_email', 'phone', 'city', 'course', 'source','query', 'posted_at')
                    ->join('forms', 'forms.id', '=', "$tablename.form_id")
                    ->where("$tablename.status",1)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get()->keyBy('form_id')->toArray();
		
        //convert to array without model
		$list = array_map(function ($value) {
		    return (array)$value;
		}, $list);


        for ($i=1; $i < 5; $i++) { 
        	$tablename = followuptablename($i);
        	$table = followuptable($i)->where("$tablename.status",1)
                    ->where('reentry',0)
                    ->orderBy('updated_at', 'desc')
                    ->get()->keyBy('form_id')->toArray();

            $table = array_map(function ($value) {
			    return (array)$value;
			}, $table);

            foreach ($table as $key => $value) {
            	//$row = array('' => , );
           		//dd($key);
           		//dd($value['counsellor_id']);
           		$value['counsellor_id'] = $counsellorlist[$value['counsellor_id']]['firstname'];
           		
            	$list[$key][$i] = $value;
            }
        }
    	
    	
		return "Success";
    }*/

    public function generateRemarksReport(Request $request) {

        //DB::update("UPDATE forms LEFT JOIN ( SELECT id,COUNT(id) AS counter FROM forms GROUP BY email ) dup ON forms.id = dup.id SET `dupcheck`=1  WHERE counter IS NULL");

    	$lastforminreport = DB::table('remarks_report')->orderBy('form_id', 'desc')->first();
    	$lastforminreportid = !empty($lastforminreport) ? $lastforminreport->form_id : 0;
    	$counsellorlist = getCounsellor();
    	$_SESSION["counsellorlist"] = $counsellorlist;

    	// //Insert Newely Added Forms
    	ReportGenerator::importQueryForms($lastforminreportid);

    	//Update Followups
    	for ($i=1; $i <= 5; $i++) { 
        	$tablename = followuptablename($i);

        	$coulunname = 'followup_'.$i.'_updated';
        	$lastfollowupid = DB::table('options')->where('name', $coulunname)->first();
        	// if($i == 5) {
        	// 	break;
        	// }
        	
            ReportGenerator::importFollowupsUpdate($lastfollowupid->value, $i);
            
        }

        return "Success";

    }

    public function importQueryForms($lastid)
    {
    	
    	$forms = MyForm::where('id','>',$lastid)->where('dupcheck' , 0)->orderBy('id', 'asc')->limit(500)->get();
    	$data = array();
    	foreach ($forms as $key => $form) {
    		array_push($data, 
	        	array(
					'form_id'    => $form->id, 
					'name'       => $form->name, 
					'email'      => $form->email, 
					'phone'      => $form->phone, 
					'city'       => $form->city, 
					'course'     => $form->course, 
					'source'     => $form->source, 
					'query'      => $form->query, 
                    'status'     => $form->status, 
					'posted_on'  => $form->posted_at, 
	        	)
	        ); 
	        $lastid = $form->id;
	        //var_dump($lastid);
    	}
    	//dd('df');
    	DB::table('remarks_report')->insert($data);

    	$countnew = MyForm::where('id','>',$lastid)->where('dupcheck' , 0)->count();
    	if($countnew > 0) {
    		ReportGenerator::importQueryForms($lastid);	
    	}
    	
    }

    public function importFollowupsUpdate($lastid, $followupnum)
    {
    	$tablename = followuptablename($followupnum);
    	$table = followuptable($followupnum)   //->where("$tablename.status",1)
    				->where('id','>',$lastid)	
                    ->where('reentry',0)
                    ->orderBy('id', 'asc')
                    ->limit(500)
                    ->get();
    	
    	foreach ($table as $key => $followup) {
    		$followuptext = followuptext($followupnum);
    		
    		$counsellorname = $_SESSION["counsellorlist"][$followup->counsellor_id]['firstname'];

    		$data = array(
				$followuptext.'_counsellor_name'     => $counsellorname, 
				$followuptext.'_counsellor_date'     => $followup->updated_at, 
				$followuptext.'_counsellor_category' => $followup->category, 
				$followuptext.'_counsellor_remarks'  => $followup->comment 
	        );

        	DB::table('remarks_report')->where('form_id' , $followup->form_id)->update($data);
	         
	        $lastid = $followup->id;
    	}
    	

    	$countnew = followuptable($followupnum)
    				->where("$tablename.status",1)
    				->where('id','>',$lastid)	
                    ->where('reentry',0)
                    ->orderBy('id', 'asc')
                    ->count();
                    
        //Update Index
        $coulunname = 'followup_'.$followupnum.'_updated';
        //DB::table('options')->where('name' , $coulunname)->update(['value' => $lastid]);

    	if($countnew > 0) {
    		ReportGenerator::importFollowupsUpdate($lastid, $followupnum);	
    	}
    	
    }


}
