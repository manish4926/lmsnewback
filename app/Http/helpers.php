<?php
use App\Model\User;
use App\Model\MyForm;
use App\Model\Followup;
use App\Model\VerifyHotLead;
//use DB;

function getIpAddress()
{
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function ceiling($number, $significance = 1)
{
    return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}

function getdiscountvalue($total,$discount)
{
    $newprice = round($total - ($total * ($discount / 100)));
    return $newprice;
}

function timestampToTime($date,$addDays = 0){
    if($addDays != 0)
    {
        $date = strtotime("+".$addDays." days",$date);
    }
    else{
        $date = strtotime($date);
    }
}

function timestampToDate($date,$addDays = 0){
    if($addDays != 0)
    {
        $date = date('d/m/Y',strtotime("+".$addDays." days",$date));
    }
    else{
        $date = date('d/m/Y',strtotime($date));
    }
    return $date;
    //date('d/m/Y',strtotime("+14 days",$order->deliveredon));
}

function dateToTimestamp($date,$addDays = 0){

    if($addDays != 0)
    {
        $date = date('Y-m-d H:i:s',strtotime("+".$addDays." days",$date));
    }
    else{
        
        $date = date('Y-m-d H:i:s',strtotime($date));
    }
    return $date;
}

function seoUrl($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}


function GetCoreInformation() {
    $data = file('/proc/stat');
    $cores = array();
    foreach( $data as $line ) {
        if( preg_match('/^cpu[0-9]/', $line) )
        {
            $info = explode(' ', $line );
            $cores[] = array(
                'user' => $info[1],
                'nice' => $info[2],
                'sys' => $info[3],
                'idle' => $info[4]
            );
        }
    }
    return $cores;
}

function GetCpuInfo()
{
    //Average Load
    $avgload = sys_getloadavg();
    $array = array();
    array_push($array, array("Average Load" => $avgload[0]));

    //Cpu Usage
    /* get core information (snapshot) */
    $stat1 = GetCoreInformation();
    /* sleep on server for one second */
    sleep(1);
    /* take second snapshot */
    $stat2 = GetCoreInformation();
    /* get the cpu percentage based off two snapshots */
    $data = GetCpuPercentages($stat1, $stat2);
    $a = 0;
    $count = count($data);
    foreach ($data as $cpu ) {
        //dd($cpu['user']);
        $a +=  $cpu['user']+$cpu['sys'];

    }
    $b = round($a/$count);
    array_push($array, array("Cpu Usage" => $b));

    //Memory Usage (Not Yet Working Properly)
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    //dd($data);
    array_pop($data);
    foreach ($data as $line) {
        $val = explode(":", $line);
        $ram = trim($val['1']);

        $meminfo['key'] = $ram;
    }
    array_push($array, $meminfo);  
    $newram = get_server_memory_usage();
    array_push($array, $newram);  

    return $array;
}


function GetCpuPercentages($stat1, $stat2) {
    if( count($stat1) !== count($stat2) ) {
        return;
    }
    $cpus = array();
    for( $i = 0, $l = count($stat1); $i < $l; $i++) {
        $dif = array();
        $dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
        $dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
        $dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
        $dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
        $total = array_sum($dif);
        $cpu = array();
        foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);
        $cpus['cpu' . $i] = $cpu;
    }
    return $cpus;
}

function GetRamInformation()
{
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    array_pop($data);
    foreach ($data as $line) {
        $val = explode(":", $line);
        $ram = trim($val['1']);

        $meminfo['key'] = $ram;


    }
    return $meminfo;
}

function get_server_memory_usage(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    //$memory_usage = $mem[2]/$mem[1]*100;

    return $free_arr;
}

function followuptext($i) {
    if($i == 1) {
        return "first";
    }
    elseif($i == 2) {
        return "second";
    }
    elseif($i == 3) {
        return "third";
    }
    elseif($i == 4) {
        return "fourth";
    }
    elseif($i == 5) {
        return "fifth";
    }
    elseif($i == 6) {
        return "sixth";
    }
    else {
       return "Limit Extended"; 
    }
}

function followuptexttoCount($i) {
    if($i == "First") {
        return 0;
    }
    elseif($i == "Second") {
        return 1;
    }
    elseif($i == "Third") {
        return 2;
    }
    elseif($i == "Forth") {
        return 3;
    }
    elseif($i == "Fifth") {
        return 4;
    }
    else {
       return "Limit Extended"; 
    }
}

function fetchSslApi($url) {
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    ); 
    $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
    return $json;
}

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {

    // search and remove comments like /* */ and //
    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
    $json = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json), true );
    //$json = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":([a-zA-Z]+[a-zA-Z0-9_]*)/','$1:$2',$json);
    //$json = preg_replace('/"([a-zA-Z_]+[a-zA-Z0-9_]*)":/','$1:',$json);

    /*if(version_compare(phpversion(), '5.4.0', '>=')) { 

        return json_decode($json, $assoc, $depth, $options);
    }
    elseif(version_compare(phpversion(), '5.3.0', '>=')) { 
        return json_decode($json, $assoc, $depth);
    }
    else {
        return json_decode($json, $assoc);
    }*/
    return $json;
}

function send_message($number,$message){
echo "<pre>";
    $user = config('services.sms')['client_id'];
    $password = config('services.sms')['client_secret'];
    $sender_id = config('services.sms')['client_senderid'];
    var_dump($user);
    var_dump($password);
    var_dump($sender_id);
    $priority = 'ndnd';
    $sms_type = 'normal';
    $data = array('user'=>$user, 'pass'=>$password, 'sender'=>$sender_id, 'phone'=>$number, 'text'=>$message, 'priority'=>$priority, 'stype'=>$sms_type);//
    $ch = curl_init('http://bhashsms.com/api/sendmsg.php?');
    echo var_dump($data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    echo var_dump($ch);
    try {
        $response = curl_exec($ch);
        echo var_dump($ch);
        curl_close($ch);
    }catch(Exception $e){
        echo 'Message: ' .$e->getMessage();
    }
    dd('Hello');
}

function text_local_api($number, $message) {
  /*require app_path().'textlocal.class.php';
  $Textlocal = new Textlocal(false, false, '5GbKNB0GVRE-wdVQirMi4Lsq54EbbzbYrXTREpf77s');
 
    $numbers = array($number);
    $sender = 'JIMSRH';
 
    $response = $Textlocal->sendSms($numbers, $message, $sender);
    print_r($response);
    dd('fdfdf');*/

    $apiKey = urlencode('5GbKNB0GVRE-wdVQirMi4Lsq54EbbzbYrXTREpf77s');
    
    // Message details
    $numbers = urlencode($number);
    $sender = 'JIMSRH';
    $message = rawurlencode($message);
 
    // Prepare data for POST request
    $data = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
 
    // Send the GET request with cURL
    $ch = curl_init('http://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    // Process your response here
    echo $response;
}


function getGDPI($code) {
    $gdpiarr = [
        '00' => 'N.A.',
        '01' => 'PGDM',
        '02' => 'Selected PGDM, Offer PGDM-IB',
        '03' => 'Regret PGDM But Option for IB',
        '04' => 'Waiting PGDM',
        '05' => 'Not Selected',
        '06' => 'PGDM-IB',
        '07' => 'PGDM-RM',
        '08' => 'Regret PGDM But Option for PGDM-RM',
        '09' => 'Selected PGDM, Offer PGDM-IB,PGDM-RM',
        '10' => 'Selected PGDM, Offer PGDM-RM',
        '11' => 'Selected PGDM-IB Offer PGDM-RM',
        '12' => 'Regret PGDM But Option for PGDM-IB And PGDM-RM',
        '13' => 'Provisional PGDM',
        '14' => 'Provisional PGDM-RM',
        '15' => 'Provisional PGDM-IB'
    ];
    return $gdpiarr[$code];
}

function get7DaysDates($days, $format = 'd/m'){
    $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)) ; 
    }
    return array_reverse($dateArray);
}

function in_array_r($needle, $haystack, $strict = false) {  //In_array for Multidimention
  foreach ($haystack as $item) {
      if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
          return true;
      }
  }

  return false;
}

function searchArrayMultidimentionFollowups($value,$indexkey, $array) {
   $findkeylist = array();
   foreach ($array as $key => $val) {
       if ($val[$indexkey] === $value) {
        
            //$k = ['key' => $key, 'level' => $val['level']];
           //return $key;
            array_push($findkeylist, $key);
       }
   }
   if(!empty($findkeylist)) {
        return $findkeylist;
   }
   return null;
}


function getAllDatesbetweenTwoDates($startdate, $enddate) {
    $start = strtotime($startdate);
    $stop = strtotime($enddate);
    $arr = array();
    for ($i=$start; $i<=$stop; $i+=86400)
    {
        $date = date("Y-m-d", $i);
        array_push($arr, $date);
    }
    return $arr;
}

function getCounsellor()
{
    $counsellors = User::get()->keyBy('id')->toArray();
    return $counsellors;
}

function getCounsellorOnly()
{
    $counsellors = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->whereNotIn('id', [5,6, 15,16,17])
                    ->get()
                    ->keyBy('id')->toArray();
    return $counsellors;
}



function getSourceDataforGraph()
{
    /* Source Data*/
    $sourcedata    = MyForm::select('source', DB::raw('count(id) as total'))
                        ->whereNotIn('course', ['BBA', 'BCA', 'MCA', 'Fellowship Programme in Management', 'FPM', 'FPM programme'])
                        ->groupBy('source')
                        ->get();

    $sourcedata_chart = array(['Source', 'Total Counts']);

    foreach ($sourcedata as $source) {
        $arr = array($source->source, (int)$source->total);
        array_push($sourcedata_chart, $arr);
    }

    return $sourcedata_chart;
}

function getRemarksData()
{
    $sourcedata    = Followup::select('comment', DB::raw('count(*) as total'))
                        ->groupBy('comment')
                        ->get()->toArray();

    $arr = array(
        'Not Interested' => 0,
        'Switch Off' => 0,
        'Not Reachable' => 0,
        'Concerned Person Not Available' => 0,
        'Call Back Later' => 0,
        'Call Disconnected' => 0,
        'Wrong Number' => 0,
        'Request to Call' => 0,
        'Others' => 0,
    );

    $remarksdata_chart = array(['Remarks', 'Total Counts']);

    foreach ($sourcedata as $source) {
        if (strpos(strtolower($source['comment']), strtolower('not interested')) !== false) {
            $arr['Not Interested'] = $arr['Not Interested'] + $source['total'];
        } 
        elseif(strpos(strtolower($source['comment']), strtolower('Switch Off')) !== false) {
            $arr['Switch Off'] = $arr['Switch Off'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Not Reachable')) !== false) {
            $arr['Not Reachable'] = $arr['Not Reachable'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Concerned Person Not Available')) !== false) {
            $arr['Concerned Person Not Available'] = $arr['Concerned Person Not Available'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Call Back Later')) !== false) {
            $arr['Call Back Later'] = $arr['Call Back Later'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Call Disconnected')) !== false) {
            $arr['Call Disconnected'] = $arr['Call Disconnected'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Wrong Number')) !== false) {
            $arr['Wrong Number'] = $arr['Wrong Number'] + $source['total'];
        }
        elseif(strpos(strtolower($source['comment']), strtolower('Request to Call')) !== false) {
            $arr['Request to Call'] = $arr['Request to Call'] + $source['total'];
        }
        else {
            $arr['Others'] = $arr['Others'] + $source['total'];
        }

    }

    foreach ($arr as $key => $value) {
        $data = array($key, (int)$value);
        array_push($remarksdata_chart, $data);
    }

                        
    return $remarksdata_chart;
}

function getHitUrl ($website) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $website);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}


function perc_calc($newval, $totalval) {
  /*$new_width = ($percentage / 100) * $totalWidth;*/
  if($totalval > 0) {
    $perc    = round2(($newval * 100) / $totalval);  
  } else {
    $perc = 0;
  }
  
  return $perc;
}


function perc_val_calc($perc, $totalval) {
  $newvalue = ($perc  * $totalval) / 100;  
  return $newvalue;
}

function round2($value) {
  return round($value,2);
}

function getSendInBlueIdByName($alias_name) {
    $campaign = [
        '197' => 'information-brochure-2018',
        '463' => 'interested_lead_welcome_mailer',
        '583' => 'hotleads_welcome_mailer',
        '622' => 'hotleads_welcome_login_mailer'
    ];

    $key = array_search($alias_name, $campaign);
    return $key;
}

/*------------*/
function addVerificationMail($lastinsertedid,$email) {
    
    DB::table('leads_mail_sender_list')->insert(
            ['form_id' => $lastinsertedid, 'email' => $email]
        );
    
}

function random_strings($length_of_string) 
{ 
  
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  
                       0, $length_of_string); 
} 

function sendSingleSmtpHotLeadVerifyEmail($email, $formid, $name)
{
    $campaign_name = 'hotleads_welcome_mailer';
    $campaignId = getSendInBlueIdByName($campaign_name);
      
    $random_id = mt_rand(100000, 999999);
    $token = Str::random(40);
        

    $check = VerifyHotLead::where('email',$email)->first();
    if(empty($check)):
        VerifyHotLead::insert(
            ['form_id' => $formid, 'email' => $email, 'token' => $token]
        );

        $verfication_link = route('hotleadverify',$token);
        $form = MyForm::select('name','email')->where('id', $formid)->first();       

        //$email = 'manish.arora@jimsindia.org';
        $array = [
        'templateId' => $campaignId,
        'to' => [ 
            ['name' => $name,  'email'=> $email] 
        ], 
        'params' => [
                'FIRSTNAME'  => $name,
                'EMAIL'      => $email,
                'RAND_ID'    => $random_id,
                'VERIFYLINK' => $verfication_link
            ] 
        ]; //Based on format selected in campaign
        
        $response = sendSMTPMAIL($array);
        
    endif;

    return "success";
}

function sendtoEmailAutomation($email, $source) {

    $currentdate = date('Y-m-d');

    $flow = DB::table('emailflowchart')
                ->where('source', $source)
                ->where('parent_id', 0)
                ->first();

    if(!empty($flow)) {
        $newdate = date('Y-m-d', strtotime($currentdate. ' + '.$flow->delay_days.' days'));

        DB::table('emailautomationhitter')->insert([['source' => $source,'email' => $email, 'hit_date' => $newdate,'type' => 'Category','parent_id' => 0]]);    
        //DB::table('emailautomationhitter')->insert([['source' => $source,'email' => $email, 'hit_date' => $newdate,'type' => 'General','parent_id' => 0]]);   
    }

     
}

function sendtoEmailAfterFormFillAutomation($email, $source) {


    $currentdate = date('Y-m-d');

    $flow = DB::table('emailafterformfillflow')
                ->where('source', $source)
                ->where('parent_id', 0)
                ->first();

    if(!empty($flow)) {

        $newdate = date('Y-m-d', strtotime($currentdate. ' + '.$flow->delay_days.' days'));
        DB::table('emailautomationafterformfillhitter')->insert([['source' => $source,'email' => $email, 'hit_date' => $newdate,'parent_id' => 0]]);         
    }
}



function arrayMatchKey($id, $array) {
   foreach ($array as $key => $val) {; 
        if($res = array_search($id,$val) !== false) {
            return $key;
        }
   }
   return null;
}

function myOperatorDirectCall($mobilenumber, $userid, $randomrefid)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://obd-api.myoperator.co/obd-api-v1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>'{
            "company_id": "6267b84241206165",
            "secret_token": "3bc0146ab35eb61cf86395ffcfe1c73e3a58333187e172afe9e6172aa778628d",
            "type": "1",
            "user_id": "'.$userid.'",
            "number": "+91'.$mobilenumber.'",
            "public_ivr_id": "6290a405eb29b407",
            "reference_id": "",
            "region": "",
            "caller_id": "",
            "group": ""
        }',
        CURLOPT_HTTPHEADER => array(
            "x-api-key: oomfKA3I2K6TCJYistHyb7sDf0l0F6c8AZro5DJh",
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;


}