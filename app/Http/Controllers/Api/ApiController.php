<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

use Illuminate\Support\Facades\Validator;
class ApiController extends Controller
{
    // Register api

    public function registerApi(Request $request)
    {
        
      try {

		$validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'user_role'	=> 'required',
            'plate_form'				=> 'required_if:user_role,==,2',
        ],[
				'plate_form.required_if' 			=> 'The type of platform link is required when user type is Publishers!',
			]);
       
       if($validator->fails()){
           
        return response(['status'=>false,'error' => $validator->errors()->first()]);
       
        }else{

        // $validatedData['password'] = bcrypt($request->password); 	
        $data = $request->all();

        $user = new  User();	
		$user->first_name 		= $request->get('first_name');
		$user->last_name 			= $request->get('last_name');
		$user->email 					= $request->get('email');
		//$user->password 			= Hash::make($request->get('password'));
		$user->password 			= $request->get('password');
		$user->user_role 			= $request->get('user_role');
		$user->user_status 		= 1;
		$user->save();

      	 $validatedData['user_status'] = 1;

       
        return response([ 'status'=>true,'user' => $user]);

        }
      

       
       // $accessToken = $user->createToken('authToken')->accessToken;
         } catch (\Exception $e) {
         	 return response([ 'status'=>false,'error' => $e->getMessage()]);
            
        }
       
    }


    // verify user exist by email

    public function verifyUserApi(Request $request)
    {
        
      try {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
          
        ]);

        if($validator->fails()){
            return response(['status'=>false,'error' => $validator->errors()->first()]);
        }else{


			$checkEmail = User::where([['email', '=', $request->email],['user_status','=',1]])->first();
				
			if(empty($checkEmail)){
		
			return response([ 'status'=>false,'error' => 'Email dose not exist']);
					
			}else{

			return response([ 'status'=>true,'user'=>$checkEmail,'message' => 'Already registered by this email.']);
			}

        }

         } catch (\Exception $e) {
         	 return response([ 'status'=>false,'error' => $e->getMessage()]);
            
        }
       
    }
}
