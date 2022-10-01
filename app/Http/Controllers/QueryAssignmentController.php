<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\MyForm;
use App\Model\User;


use Auth;
use DB;



class QueryAssignmentController extends Controller
{
    public function automateQueryFlow(Request $request)
    {
    	$user = Auth::user();

    	$sources = MyForm::select('source')
    					->distinct()->get();

    	$callflow = DB::table('callflow')->get();

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
    	}

    	$counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->where('status' , 1)
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get();

    	return view('queryassignment.automateflow',compact('request','user','sources','counsellors'));
    }

    public function automateQueryFlowSubmit(Request $request)
    {
    	DB::table('callflow')->insert(
            ['counsellor_array' => $request->counsellors, 'source' => $request->sources, 'flowarray' => $request->sflowc]
        );
    	return 'success';
    }

    public function importAutomateQueryFlowSubmit(Request $request)
    {
    	$flow = DB::table('callflow')->where('id', $request->flowid)->first();
    	return $flow->flowarray;
    }

    public function automateCallFlowGenerate(Request $request)
    {
        DB::table('callflowchart')->truncate();

        $flowlist = DB::table('callflow')->get();
        foreach ($flowlist as $key => $flow) {
            $counsellor_array = json_decode($flow->counsellor_array);
            $flowsource = json_decode($flow->source)[0];
            $flowsource = explode("-", $flowsource);
            unset($flowsource[0]);
            $flowsource = implode("-", $flowsource);

            foreach ($counsellor_array as $keycounsellor => $counsellor) {
                $counsellor_id = last(explode('-', $counsellor));

                DB::table('callflowchart')->insert(
                    ['counsellor_id' => $counsellor_id, 'source' => $flowsource, 'index_ratio' => 1, 'allot_ratio' => 0]
                );
            }
            
        }
        return "success";
    }

    public function allotCalls(Request $request)
    {
        
    }

}
