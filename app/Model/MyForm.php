<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BindsDynamically;

class MyForm extends Model
{
    //protected $table = 'forms';
    //protected $table = 'forms2019';
    
    protected $table = 'forms';


    public function dup()
    {
        return $this->belongsToMany('App\Model\MyForm','forms','id','id');
    }

    public function followup()
    {
        return $this->hasMany('App\Model\Followup','form_id','id');
    }

    public function user()
    {
        return $this->hasOne('App\Model\User','id','counsellor_id');
    }

    public function getuser()
    {
        $query = $this->user()->first();
        return $query;
    }

    public function followupbylevel($level) {
        /*$query = $this->followup()->where('level',$level)->first()->getuser();*/
    	$query = $this->followup()->where('level',$level)->first();
        return $query;
    }
}
