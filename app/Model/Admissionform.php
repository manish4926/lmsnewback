<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admissionform extends Model
{
    //protected $table = 'admissionforms2019';
    public function form()
    {
        return $this->hasMany('App\Model\MyForm','email','email');
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

    public function firstsource()
    {
        $query = $this->form()->select('source')->orderBy('posted_at', 'asc')->first();
        return $query;
    }

    public function allsourcelist()
    {
        $query = $this->form()->select('source')->orderBy('posted_at', 'asc')->get()->toArray();
        return $query;
    }
}
