<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inquirymaster;
use Validator;
use Illuminate\Support\Facades\Auth;

class VisitorsController extends Controller
{
    public function save_visitor(Request $request) {
        
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=>'nullable|email|unique:visitors,email',
            'phone'=>'required|numeric|unique:visitors,phone',
            'occupation'=>'numeric',
            'other_occupation'=>'required_if:occupation,==,0',
            'project_id'=>'required',
            'priority'=>'required',
            // 'current_locality'=>'required',
            // 'budget_from'=>'required',
            // 'budget_to'=>'required',
            // 'follow_up_date'=>'nullable',
            // 'follow_up_time'=>'nullable',
            // 'assign_to'=>'required',
            //'inquiry_for'=>'required',
            // 'source_type'=>'required|numeric'

        ], [
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'email.email' => 'Email should be a valid address',
            'phone.required' => 'Phone is required',
            'occupation.required' => 'Occupation should be selected',
            'other_occupation.required_if'=>'Other Occupation is required',
            'project_id.required' => 'Please provide project ID',
            'priority.required'=>'Lead Priority is required',
            // 'current_locality.required'=>'Please enter current locality',
            // 'budget_from.required'=>'Budget from is required',
            // 'budget_to.required'=>'Budget to is required',
            // 'follow_up_date.required'=>'Follow up date is required',
            // 'follow_up_time.required'=>'Follow up time is required',
            // 'assign_to.required'=>'Assign to is required',
           // 'inquiry_for.required'=>'Inquiry for is required',
            // 'source_type.required'=>'Source type is required',
            // 'source_type.numeric'=>'Source type must be numeric only'

        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
        }

        if(isset($request->title) && $request->title != ""){
            if($request->date == "" || $request->time == ""){
                return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
            }
        } 
        if($request->date != ""){
            if($request->time == "" || $request->title == ""){
                return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
            }
        }
        if($request->time != ""){
            if($request->date == "" || $request->title == ""){
                return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
            }
        }

        $project_id = $request->project_id;
        
        $user_id = Auth::id() ?? 1;
        //$user_id = $request->assign_to;
        $bhk = isset($request->bhk) ? $request->bhk : 0;   

        $project = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'mobile_code'=>$request->mobile_code ?? '',
            'phone_2'=>$request->phone2          ?? '',
            'Inquiry_date'=> isset($request->Inquiry_date) ? $request->Inquiry_date     : date('Y-m-d'),
            'occupation'=>$request->occupation,
            'project_id'=>$project_id,
            'current_locality'=>$request->current_locality,
            'budget_from'=>$request->budget_from,
            'budget_to'=>$request->budget_to,
            // 'follow_up_date'=>\Helper::date2db($request->follow_up_date),
            // 'follow_up_time'=>$request->follow_up_time,
            'assign_to'=>isset($request->assign_to) ? $request->assign_to : $user_id,
            'inquiry_for'=>isset($request->inquiry_for) ? $request->inquiry_for : 0,
            'priority'=>$request->priority,
            'bhk'=>$bhk,
            'remarks'=>$request->remarks,
            'other_occupation'=>$request->other_occupation,
            'created'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'inquiry_status_id'=>1,
            'source_type'=>$request->source_type,
            'other_source_type'=>isset($request->other_source_type) ? $request->other_source_type : null,
            'budgetrange_id'=>isset($request->budgetrange_id) ? $request->budgetrange_id : '0',
            'brokers_id'=>isset($request->brokers_id) ? $request->brokers_id : NULL,
            'dob'                      => isset($request->dob) ? $request->dob :  null,
            'anniversary_date'         => isset($request->anniversary_date) ? $request->anniversary_date : null,
            'cast_type'                => isset($request->cast_type) ? $request->cast_type : '0',
            'project_status_type'      => isset($request->project_status_type) ? $request->project_status_type : '0',
            'source_type_sub_category' => isset($request->source_type_sub_category) ? $request->source_type_sub_category : '0',
            
        ];
        //dd($project);
        $lastid = \DB::table('visitors')->insertGetId($project);      
        // $lastid = $this->saveFollowers($request);
        return response()->json(['msg' => 'Visitor has been created successfully','data'=>[], 'status' => true]);
    }

    // public function update_visitor(Request $request) {
    //     $visitor_id = isset($request->visitor_id) ? $request->visitor_id : 0; 
    //     $validator = \Validator::make($request->all(), [
    //         'first_name' => 'required',
    //         'last_name' => 'required',
    //         'email'=>'nullable|email|unique:visitors,email,' . $visitor_id,
    //         'phone'=>'required|numeric|unique:visitors,phone,' . $visitor_id,
    //         'occupation'=>'numeric',
    //         'other_occupation'=>'required_if:occupation,==,0',
    //         'project_id'=>'required',
    //         'priority'=>'required',
    //         // 'current_locality'=>'required',
    //         // 'budget_from'=>'required',
    //         // 'budget_to'=>'required',
    //         // 'follow_up_date'=>'nullable',
    //         // 'follow_up_time'=>'nullable',
    //         // 'assign_to'=>'required',
    //         //'inquiry_for'=>'required',
    //         // 'source_type'=>'required|numeric'

    //     ], [
    //         'first_name.required' => 'First Name is required',
    //         'last_name.required' => 'Last Name is required',
    //         'email.email' => 'Email should be a valid address',
    //         'phone.required' => 'Phone is required',
    //         'occupation.required' => 'Occupation should be selected',
    //         'other_occupation.required_if'=>'Other Occupation is required',
    //         'project_id.required' => 'Please provide project ID',
    //         'priority.required'=>'Lead Priority is required',
    //         // 'current_locality.required'=>'Please enter current locality',
    //         // 'budget_from.required'=>'Budget from is required',
    //         // 'budget_to.required'=>'Budget to is required',
    //         // 'follow_up_date.required'=>'Follow up date is required',
    //         // 'follow_up_time.required'=>'Follow up time is required',
    //         // 'assign_to.required'=>'Assign to is required',
    //        // 'inquiry_for.required'=>'Inquiry for is required',
    //         // 'source_type.required'=>'Source type is required',
    //         // 'source_type.numeric'=>'Source type must be numeric only'

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
    //     }

    //     if(isset($request->title) && $request->title != ""){
    //         if($request->date == "" || $request->time == ""){
    //             return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
    //         }
    //     } 
    //     if($request->date != ""){
    //         if($request->time == "" || $request->title == ""){
    //             return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
    //         }
    //     }
    //     if($request->time != ""){
    //         if($request->date == "" || $request->title == ""){
    //             return response()->json(['msg' =>  ['followup' => "Please enter proper follow-up details"], 'data' => [], 'status' => false]);
    //         }
    //     }

    //     $project_id = $request->project_id;
        
    //     $user_id = Auth::id() ?? 1;
    //     //$user_id = $request->assign_to;
    //     $bhk = isset($request->bhk) ? $request->bhk : 0;   

    //     $project = [
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'email'=>$request->email,
    //         'phone'=>$request->phone,
    //         'mobile_code'=>$request->mobile_code ?? '',
    //         'phone_2'=>$request->phone2          ?? '',
    //         'Inquiry_date'=> isset($request->Inquiry_date) ? $request->Inquiry_date     : date('Y-m-d'),
    //         'occupation'=>$request->occupation,
    //         'project_id'=>$project_id,
    //         'current_locality'=>$request->current_locality,
    //         'budget_from'=>$request->budget_from,
    //         'budget_to'=>$request->budget_to,
    //         // 'follow_up_date'=>\Helper::date2db($request->follow_up_date),
    //         // 'follow_up_time'=>$request->follow_up_time,
    //         'assign_to'=>isset($request->assign_to) ? $request->assign_to : $user_id,
    //         'inquiry_for'=>isset($request->inquiry_for) ? $request->inquiry_for : 0,
    //         'priority'=>$request->priority,
    //         'bhk'=>$bhk,
    //         'remarks'=>$request->remarks,
    //         'other_occupation'=>$request->other_occupation,
    //         'created'=>date('Y-m-d H:i:s'),
    //         'user_id'=>$user_id,
    //         'inquiry_status_id'=>1,
    //         'source_type'=>$request->source_type,
    //         'other_source_type'=>isset($request->other_source_type) ? $request->other_source_type : null,
    //         'budgetrange_id'=>isset($request->budgetrange_id) ? $request->budgetrange_id : '0',
    //         'brokers_id'=>isset($request->brokers_id) ? $request->brokers_id : NULL,
    //         'dob'                      => isset($request->dob) ? $request->dob :  null,
    //         'anniversary_date'         => isset($request->anniversary_date) ? $request->anniversary_date : null,
    //         'cast_type'                => isset($request->cast_type) ? $request->cast_type : '0',
    //         'project_status_type'      => isset($request->project_status_type) ? $request->project_status_type : '0',
    //         'source_type_sub_category' => isset($request->source_type_sub_category) ? $request->source_type_sub_category : '0',
            
    //     ];
    //     //dd($project);
    //     $lastid = \DB::table('visitors')->where('id',$visitor_id)->update($project);      
    //     // $lastid = $this->saveFollowers($request);
    //     return response()->json(['msg' => 'Visitor has been updated successfully','data'=>[], 'status' => true]);
    // }


    public function listening_visitor(Request $request) {
        // $visitor_id = isset($request->visitor_id) ? $request->visitor_id : 0; 
        // $validator = \Validator::make($request->all(), [
        //     'visitor_id' => 'required',
        // ], [
        //     'visitor_id.required' => 'First Name is required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
        // } 

        //dd($project);
        $data = \DB::table('visitors')->get();
        // $lastid = $this->saveFollowers($request);
        return response()->json(['status' => true,'data'=>$data]);
    }

    public function details_visitor(Request $request) {
        $visitor_id = isset($request->visitor_id) ? $request->visitor_id : 0; 
        $validator = \Validator::make($request->all(), [
            'visitor_id' => 'required',
        ], [
            'visitor_id.required' => 'First Name is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
        } 

        //dd($project);
        $data = \DB::table('visitors')->where('id',$visitor_id)->first();
        // $lastid = $this->saveFollowers($request);
        return response()->json(['status' => true,'data'=>$data]);
    }
}
