<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class Usercontroller extends Controller
{
    public function __construct(Request $request)
    {

        $this->middleware('auth:api', ['except' => ['login','forgot_pwd','forgot_pwd_otp_update','forgot_update_new_pwd','register','refreshToken']]);
        // $project_id = \Helper::getProjetidByrequestapi($request);
        // if($project_id > 0) {
        //     // \Helper::connectToProjectDBbyId($project_id);
        // }

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 300);
    }

    public function register(Request $request)
    {
        $currentdate = date('Y-m-d h:i:s');
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|max:50|email|unique:users,email',
            'password' => 'required|max:10|min:6|same:confirm_password',
            'confirm_password'=>'required',
            'phone' => 'required|digits:10',
        ], [
            'first_name.required' => 'first name is required',
            'last_name.required' => 'last name is required',
            'email.required' => 'email is required',
            'phone.required' => 'phone from is required',
            'password.required'=>'Password is required',
            'password.min'=>'Password should be minimum 6 characters',
            'password.max'=>'Password can be maximum 10 chatacters',
            'password.required_with'=>'Password and confirm password should be same',
            'confirm_password.required'=>'Confirm Password is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
        }
        $user = new User;
        $otp = rand(100000, 999999);
        $email_otp = rand(100000, 999999);
        $inputs = $request->all();
        $inputs['otp'] = $otp;
        $inputs['email_otp'] = $email_otp;
        $inputs['password'] = \Hash::make($request->password);
        $inputs['confirm_password'] = \Hash::make($request->confirm_password);
        $inputs['created_at'] = $currentdate;
        
        $userlastid = $user->insertGetId($inputs);
        session()->put('registered_user', $userlastid);
        $data = [];
        // $data['mobile_otp'] = $inputs['otp'];
        // $data['email_otp'] = $inputs['email_otp'];
        $data['user_id'] = $userlastid; 
        $info = User::select('id','first_name','last_name','email','phone')->where('id',$userlastid)->first();
        $data = $info;

        // Token start
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // $data['token'] = $token;
        // Token end
        
        // \Helper::sendEmailOTPEmail($info);
        // \Helper::sendSMS($info);
        
        return response()->json(['msg' => trans('added successfully'),'data'=>$data, 'status' => true]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            //'fcm_token' => 'required'
        ], [
            'email.required' => 'email is required',
            'password.required' => 'password  is required',
            //'fcm_token' => 'Please provide FCM Token'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors(),'data'=>[], 'status' => false]);
        }
        // dd(Auth::attempt(['email' => request('email'), 'password' => request('password')]));
        if (!$token = Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return response()->json(['msg' => 'Invalid credentials','data'=>[], 'status' => false]);
        } else {
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
            $user = Auth::user();
            $success = [];
            $info = User::select('id','first_name','last_name','email','phone')->where('id',$user->id)->first();
            $success['user'] = User::select('id','first_name','last_name','email','phone')->where('id',$user->id)->first();
            $fcm_token = isset($request->fcm_token) ? $request->fcm_token : '';
            \DB::table('users')->where('id',$user->id)->update(['fcm_token'=>$fcm_token,'token' =>$token]);
            $success['token'] = $token;
            // dd($token,$user);
            return response()->json(['msg' => 'You have successfully logged in','data'=>$success, 'status' => true]);
        }
    }
}
