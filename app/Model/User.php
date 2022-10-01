<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function roles()
    {
        return $this->belongsToMany('App\Model\Role','user_role','user_id','role_id');
    }

    public function profile()
    {
        return $this->belongsToMany('App\Model\Profiler','user_profile','user_id','id');
        
    }

    public function getprofiler()
    {
        return $this->hasMany('App\Model\Profiler','user_id','id');
    }


    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasAnyRole($roles)
    {
        if(is_array($roles))
        {
            foreach ($roles as $role) {
                if($this->hasRole($role)) {
                    return true;
                }
            }
        }
        else{
            if($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }
    public function hasRole($role)
    {
        if($this->roles()->where('name',$role)->first()) {
            return true;
        }
        return false;
    }
    public function user_roles()
    {
        $query = $this->roles()->first();
        return $query;
    }


    public function followup()
    {
        return $this->hasOne('App\Model\Followup','counsellor_id','id');
    }

    public function followupcountbydate($date, $level = NULL) {    //level refers to followup level
        
        $query = $this->followup()->select('counsellor_id',DB::raw('count(*) as counter'))
                                ->where(DB::raw('Date(updated_at)') ,'=', $date)
                                ->where('status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->groupBy('counsellor_id')
                                ->orderBy('updated_at', 'desc')
                                ->first();

        return $query;
    }

    public function followupcountbydatebetweenCombined($startdate, $enddate) {    //level refers to followup level
        /*$query = $this->followup()->where('level',$level)->first()->getuser();*/
        $query = $this->followup()->select('counsellor_id','level',DB::raw('count(*) as counter'))
                                ->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
                                ->where('status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
        if(!empty($keyword)) {
            $query = $query->where('forms.source' ,'like', '%$keyword%');
        }

        $query = $query->groupBy('level')
                                ->orderBy('level', 'asc')
                                ->get()->keyBy('level');

        return $query;
    }

    public function followupcountbydatebetweenCombinedMAT($startdate, $enddate) {    //level refers to followup level
        $query = $this->followup()->select('counsellor_id','level',DB::raw('count(*) as counter'))
                                ->join('forms', 'forms.id', '=', 'followups.form_id')
                                ->whereBetween(DB::raw('Date(followups.updated_at)'), [$startdate, $enddate])
                                ->where('forms.source' ,'like', '%MAT%')
                                ->where('followups.status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);

        $query = $query->groupBy('level')
                                ->orderBy('level', 'asc')
                                ->get()->keyBy('level');

        return $query;
    }

    public function followupcountbydatebetween($startdate, $enddate, $level = NULL) {    //level refers to followup level
        /*$query = $this->followup()->where('level',$level)->first()->getuser();*/
        $query = $this->followup()->select('counsellor_id',DB::raw('count(*) as counter'))
                                ->whereBetween(DB::raw('Date(updated_at)'), [$startdate, $enddate])
                                ->where('status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->groupBy('counsellor_id')
                                ->orderBy('updated_at', 'desc')
                                ->first();

        return $query;
    }

    public function followupcountbydatebetweenMATData($startdate, $enddate, $level = NULL) {    //level refers to followup level
        /*$query = $this->followup()->where('level',$level)->first()->getuser();*/
        $query = $this->followup()->select('counsellor_id',DB::raw('count(*) as counter'))
                                ->join('forms', 'forms.id', '=', 'followups.form_id')
                                ->whereBetween(DB::raw('Date(followups.updated_at)'), [$startdate, $enddate])
                                ->where('forms.source' ,'like', '%MAT%')
                                ->where('followups.status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->groupBy('counsellor_id')
                                ->orderBy('followups.updated_at', 'desc')
                                ->first();

        return $query;
    }

    public function followupStatusCountbydatebetween($startdate, $enddate, $level = NULL) {    //level refers to followup level
        $query = $this->followup()->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
                                ->join('forms', 'forms.id', '=', 'followups.form_id')
                                ->whereBetween(DB::raw('Date(followups.updated_at)'), [$startdate, $enddate])
                                ->where('followups.status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->groupBy('forms.status')
                                ->orderBy('followups.updated_at', 'desc')
                                ->get()->keyBy('status');

        return $query;

    }

    public function followupStatusCountbydatebetweenMATData($startdate, $enddate, $level = NULL) {    //level refers to followup level
        $query = $this->followup()->select('counsellor_id','forms.status',DB::raw('count(*) as counter'))
                                ->join('forms', 'forms.id', '=', 'followups.form_id')
                                ->whereBetween(DB::raw('Date(followups.updated_at)'), [$startdate, $enddate])
                                ->where('forms.source' ,'like', '%MAT%')
                                ->where('followups.status' ,'=', 1)
                                ->where('message_type' ,'=', '')
                                ->where('reentry' ,'=', 0);
                                

        if(isset($level)) {    
            $query->where('level', $level);
        }
        $query = $query->groupBy('forms.status')
                                ->orderBy('followups.updated_at', 'desc')
                                ->get()->keyBy('status');

        return $query;
    }


    public function getProfile()
    {
        $query = $this->getprofiler()->get()->keyBy('category')->toArray();;
        return $query;
    }
}
