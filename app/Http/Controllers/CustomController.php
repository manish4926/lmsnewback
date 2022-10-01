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
use App\Model\Admission;

use Auth;
use Session;
use Excel;
use SendinBlue;
use View;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Carbon\Carbon;
use App\Http\Requests;

class CustomController extends Controller
{
	public function deletecustomfollowups()
	{
		$followups = Followup::where('comment', 'Query Closed By Admin(Manish)')
						->where(DB::raw('Date(updated_at)') ,'=', '2019-05-24')
						->groupBy('form_id')
						->get();

		$idlist = array();

		foreach ($followups as $value) {
			# code...
			//dd($value->form_id);

			array_push($idlist, $value->form_id);
			
		}

		MyForm::whereIn('id', $idlist)
			    ->update([
			      'status' => 'open'
			    ]);

		Followup::where('comment', 'Query Closed By Admin(Manish)')
						->where(DB::raw('Date(updated_at)') ,'=', '2019-05-24')
						->delete();
	}	

	public function updateCustomQuery() 
	{
		$followups = Followup::select('form_id')
						->groupBy('form_id')
						->get();

						$arr = array();

						foreach ($followups as $key => $value) {
							array_push($arr, $value->form_id);
							//dd($value->form_id);
						}

		MyForm::whereIn('id', $arr)
            ->update(['dupcheck' => 0]);	
		//dd($arr);
	}

}