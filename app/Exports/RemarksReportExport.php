<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use DB;

class RemarksReportExport implements FromArray
{
    public function array(): array
    {
        $myforms = DB::table('remarks_report')->orderBy('id', 'desc')->get()->toArray();

        $myforms = array_map(function ($value) {
            return (array)$value;
        }, $myforms);

        $data = array(
            'S. No.', 'Form ID','Name','Email','Phone','City','Course', 'Source','Query', 'Status','Dated On','First Followup Counsellor', 'First Counsellor Date', 'First Followup Category','First Followup Remarks','Second Followup Counsellor', 'Second Followup Date', 'Second Followup Category','Second Followup Remarks','Third Followup Counsellor', 'Third Followup Date', 'Third Followup Category','Third Followup Remarks','Fourth Followup Counsellor', 'Fourth Followup Date', 'Fourth Followup Category','Fourth Followup Remarks','Fifth Followup Counsellor', 'Fifth Followup date', 'Fifth Followup Category','Fifth Followup Remarks'
        );

        array_unshift($myforms,$data);  
        return [
            $myforms
        ];
    }
}