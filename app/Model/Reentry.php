<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reentry extends Model
{
    protected $table = 'reentry';

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
}
