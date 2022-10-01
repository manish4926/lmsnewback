<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Auth;
use App\Model\Formconversioreport;
use Carbon\Carbon;

class FormConversionReportExport implements FromArray
{
    public function array(): array
    {
        $admissionforms = Formconversioreport::select('id','regid','name','email','programme_af', 'date' ,'firstsource','allsources')->orderBy('id', 'asc')->get()->toArray();

        $admissionforms = array_map(function ($value) {
            $value['date'] = date('Y-m-d', strtotime($value['date']));
            return (array)$value;
        }, $admissionforms);

        $data = array(
           'S. No.', 'Reg Id','Name','Email','Applied For','Dated On','First Source','All Sources'
        );

        array_unshift($admissionforms,$data);  
        return [
            $admissionforms
        ];
    }
}