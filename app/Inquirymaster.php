<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Visitor;
use Illuminate\Support\Facades\Auth;
class Inquirymaster extends Model
{
    protected $table = 'inquiry_status';
    public $timestamps = false;

    function get_visitors_api() {
        $roles_visitors_permission = \Helper::roles_sales_visitors();
        if($roles_visitors_permission == 'admin'){
        return $this->hasMany(Visitor::class,'inquiry_status_id','id')->where('status',1);
        }else{
        return $this->hasMany(Visitor::class,'inquiry_status_id','id')->where('status',1)->where('assign_to',Auth::id());
        }
    }

    function get_visitors() {  
        $role = session()->get('api_role');
        if($role == 'admin'){
            return $this->hasMany(Visitor::class,'inquiry_status_id','id')->where('status',1);
        }else{
            return $this->hasMany(Visitor::class,'inquiry_status_id','id')->where('status',1)->where('assign_to',Auth::id());
        }
       
    }

}
