<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inquirymaster;
use App\User;
use App\Roleuser;

class Visitor extends Model
{
    protected $table = 'visitors';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'project_id','user_id','first_name', 'last_name','email','phone','phone_2','priority','created','assign_to','inquiry_for','current_locality','remarks','budget_from','budget_to','import_from_excel','dob','anniversary_date','source_type','brokers_id','budgetrange_id'
    ];

    function get_inquiry() {
        return $this->belongsTo(Inquirymaster::class,'inquiry_status_id','id');
    }
    
    function get_role_user() {
        return $this->belongsTo(Roleuser::class,'assign_to','id');
    }

    function get_assigned() {
        return $this->belongsTo(User::class,'assign_to','id');
    }
}
