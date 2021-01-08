<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login
    public function mobileLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5']
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all(),'status' => 0],401);
        } else {

            if (Auth::attempt($request->only('email', 'password'))) {
                $token = auth()->user()->createToken('majiApp@token')->accessToken;
                $user =  User::where('id', $request->user()->id)->select(['id', 'name', 'email'])->first();
//                if($user['account_active'] == 0){
//                    $request->user()->token()->revoke();
//                    return response()->json(['status' => 0,'message' => 'Your account is inactive'], 404);
//                } else {
//                    return response()->json(['status' => 1, 'token' => $token, 'user' => $user], 200);
//                }
                return response()->json(['status' => 1, 'token' => $token, 'user' => $user], 200);
            } else {
                return response()->json(['status'=>0, 'message' => 'Wrong Credentials'], 404);
            }
        }
    }

    public function signUp(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>  ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:5']
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all(),'status' => 0],401);
        } else {
            $request['account_active'] = true;
            $request['password'] = Hash::make($request->input('password'));
            $user = User::create($request->only('name', 'email', 'phone_number', 'user_type_id', 'password', 'account_active'));
            return response()->json(['User'=>$user,'status' => 1],200);
        }
    }

    // check user permissions
    public function getUserPermissions(Request $request){
        $user = User::Find($request->user()->id);
        $permissions = $user->getAllPermissions();
        return response()->json(['status'=>1, 'data'=>['user'=>$user, 'permissions'=>$permissions]]);
    }

    // add permissions to a user
    public function addPermissionToUser(Request $request){
        $user = User::Find($request['user_id']);
        if($user != null){
            $permissions = $request['permissions'];
            $user->givePermissionTo($permissions);
            return response()->json(['status'=>1, 'data'=>['user'=>$user]]);
        } else {
            return response()->json(['status'=>0, 'data'=>['error'=>'User not found']], 400);
        }

    }
}
