<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VerifyHotLead extends Model
{
    protected $table = 'verify_hotleads';

    public function forms()
    {
        return $this->belongsTo('App\Model\MyForm', 'form_id');
    }
}
