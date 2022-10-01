<?php
 
namespace App\Traits;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Model\MyForm;
 
trait AssignQueryTrait {
 
    public function allotFirstQueryToCounsellor($name, $email, $mobile, $source, $lastinsertedid) {
        $resetindex = 0;
        $callflow = DB::table('callflowchart')
                    ->where('source', $source)
                    ->orderBy('allot_ratio', 'asc')
                    ->get();

        foreach ($callflow as $key => $flow) {
            if($flow->allot_ratio != $flow->index_ratio) {
                $resetindex = 1;
            }
        }

        if($resetindex == 0) {
            DB::table('callflowchart')
                ->where('source', $source)
                ->update(['allot_ratio' => 0]);  
        }

        foreach ($callflow as $key => $flow) {
            if($flow->allot_ratio < $flow->index_ratio) {
                followuptable(1)->insert([['counsellor_id' => $flow->counsellor_id, 'form_id' => $lastinsertedid,'level' => 1]]);

                MyForm::where('id', $lastinsertedid)
                            ->increment('followcounts');

                $flowcountpass = $flow->allot_ratio + 1;
                DB::table('callflowchart')
                    ->where('source', $source)
                    ->where('counsellor_id', $flow->counsellor_id)
                    ->update(['allot_ratio' => $flowcountpass]);  

                break;
            }    
        }
        return "success";
    }
 
}
 