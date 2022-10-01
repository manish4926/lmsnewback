<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Auth;
use App\Model\MyForm;
use Carbon\Carbon;

class UniqueFormsExport implements FromArray
{
    public function array(): array
    {
        $myforms = MyForm::select('id','name','email','phone','city','course','source','query','created_at','updated_at')
            ->groupBy('email')
            ->orderBy('id', 'asc')
            ->get();
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