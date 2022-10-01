<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Followup extends Model
{
    //

    public function forms()
    {
        return $this->hasMany('App\Model\MyForm','id','form_id');
    }

    public function user()
    {
        return $this->hasOne('App\Model\User','id','counsellor_id');
    }

    public function getforms()
    {
        $query = $this->forms()->first();
        return $query;
    }

    public function getuser()
    {
        $query = $this->user()->first();
        return $query;
    }

    public function getsourcebydate($date, $level = NULL) {    //level refers to followup level

        $query = $this->forms()->where(DB::raw('Date(created_at)') ,'=', $date);                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->get();

        return $query;
    }
}
