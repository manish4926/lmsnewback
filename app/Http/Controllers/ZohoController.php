<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ZohoController extends Controller
{
	
    public function generateToken(Request $request) {
    	$submit_url	= "https://accounts.zoho.in/oauth/v2/token?";

		$data = array(
		    "grant_type" => "authorization_code",
		    "client_id" => "1000.IYS823Y5MID6QI3C8R8TTQIXWXG7OZ",
		    "client_secret" => "8a709252ac040a3e7571f6c9d5ec45a06bd834778b",
		    "redirect_uri" => "https://www.jimsindia.org",
		    "code" => "1000.58b23af3b45bdc754d1f937e0b12a337.d6bd8092db0bfd3833d77157e24af46f",
		);
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $submit_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 
		$result = curl_exec($ch);
		curl_close ($ch);

		$data = json_decode($result);

		DB::table('options')->where('name', 'zoho_access_token')->update(['value' => $data->access_token]);
		DB::table('options')->where('name', 'zoho_refresh_token')->update(['value' => $data->refresh_token]);
		var_dump($data);	
    }

    public function refreshToken(Request $request) {
    	$submit_url	= "https://accounts.zoho.in/oauth/v2/token?";

    	$refresh_token = DB::table('options')->where('name', 'zoho_refresh_token')->first();

		$data = array(
		    "refresh_token" => $refresh_token->value,
		    "client_id" => "1000.IYS823Y5MID6QI3C8R8TTQIXWXG7OZ",
		    "client_secret" => "8a709252ac040a3e7571f6c9d5ec45a06bd834778b",
		    "grant_type" => "refresh_token",
		);
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $submit_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 
		$result = curl_exec($ch);
		curl_close ($ch);

		$data = json_decode($result);

		DB::table('options')->where('name', 'zoho_access_token')->update(['value' => $data->access_token]);

		var_dump($data);	
    }

    public function testinsert(Request $request) {
    	

    	DB::table('testquery')->insert([
		    'name' => $request->name,
		    'email' => $request->email,
		    'phone' => $request->phone,
		    'course' => $request->course,
		    'source' => $request->source,
		    'query' => $request->stuquery,
		]);
    }

    public function testview(Request $request) {
    	$res = DB::table('testquery')->get();

    	echo "<table border='1' >
    		<tr>
    		<th>Name</th>
    		<th>Email</th>
    		<th>Phone</th>
    		<th>Course</th>
    		<th>Source</th>
    		<th>Query</th>
    		</tr>

    	";

    	foreach ($res as $key => $value) {
    		echo "
    			<tr>
    			<td>{$value->name}</td>
    			<td>{$value->email}</td>
    			<td>{$value->phone}</td>
    			<td>{$value->course}</td>
    			<td>{$value->source}</td>
    			<td>{$value->query}</td>
    			</tr>
    		";
    	}

    	echo "</table>";
    }
}
