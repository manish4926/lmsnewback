<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Auth;
use App\Model\MyForm;
use Carbon\Carbon;

class CompleteFormsExport implements FromArray
{
    public function array(): array
    {
        $user       = Auth::user();
        $myforms    = MyForm::get();  
        $array      = array();
        $i          = 1;

        foreach($myforms as $myform)
        {
            $a  = array($i,$myform->name,$myform->email,$myform->phone,$myform->city,$myform->course,$myform->source,$myform->query,date('d-m-Y', strtotime($myform->posted_at)));
            
            array_push($array,$a);      //Adding Rows
            $i++;
        }

        $data = array(
            array('S. No.','Name','Email','Phone','City','Course', 'Source','Query','Dated On'),
        );
        foreach ($array as $row) {
            array_push($data,$row);
        }
        
        return [
            $data
        ];
    }
}