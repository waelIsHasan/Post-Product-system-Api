<?php

namespace App\Http\Controllers\Autho;

use App\Events\NewRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function register(Request $request){
       try{
        $validater = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if($validater->fails()){
            return response()->json([
                "success"=>false,
                "msg"=>"Validate Error"
                ,"errors"=>$validater->errors(),    
        ]);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']); 
        $user = User::create([
            'name'=>$input['name'],
            'email'=>$input['email'],
            'password'=>$input['password']
        ]);
        $success['token']=$user->createToken('wael')->accessToken;
   
        return response()->json([
            "success"=>true,
            "msg"=>"has registered successfully",
            "token"=>$success['token'],   
    ]);

}catch(Exception $e){
    return response()->json(["msg" => $e]);
}
}
    public function login(Request $request){
        try{
            if(Auth::attempt(['email' => $request->email , 'password' => $request->password])){
                $id = Auth::id();
                $user = User::find($id);
                $success['token'] = $user->createToken('wael')->accessToken;
                return response()->json(["success"=>true,"msg" => "You have loggin successfully " ,"token" =>$success['token']]);
        
            }
        
            else {
                return response()->json(["success"=>false ,'msg' => "Validate Error"]);
            }
    }
    catch(Exception $e){
        return response()->json(["msg" => $e]);
    }
}

public function logout(Request $request){
$token = $request->user()->token();
    $token->revoke();
    return response()->json(["msg" => "logged out successfully"]);
}
}
