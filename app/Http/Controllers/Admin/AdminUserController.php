<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\TelegramGroup;
use App\Notifications\SendNotification as Notify;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
class AdminUserController extends Controller
{
	public function save(Request $request){

		$userType = $request->get('user_hidden');
		$validated = $request->validate(
			[
				'first_name' 		=> 'required',
				'email' 				=> 'required|unique:users',
				'telegram_link'	=> 'required_if:user_hidden,==,2',
			],
			[
				'telegram_link.required_if' 			=> 'The telegram link is required when user type is Publishers!',
			]
		);

		$redirectmessage = ($userType == '3')?"Advertiser":"Publisher";
		$redirecroute = ($userType == '3')?"admin.advertisers.index":"admin.publishers.index";

		$password = rand();
		$user = new  User();
		$user->first_name = $request->get('first_name');
		$user->last_name = $request->get('last_name');
		$user->email = $request->get('email');
		$user->password = Hash::make($password);
		$user->user_status = 0;
		$user->created_by = 'admin';
		$user->user_role = $userType;
		$user->telegram_link = ($userType == '2')?$request->get('telegram_link'):'';
		$user->save();


    //   For email sending    ////
		//$userid = $user->id;
		//$token = time();

		/*$registrationToken = new  RegistrationToken();
		$registrationToken->user_id = $userid;
		$registrationToken->token = $token;
		$registrationToken->save();*/

		/*$details = [
			'name' => $request->get('first_name'),
			'email' => $request->get('email'),	
			'password' => $password,
			'mailFrom' => 'AdminRegistration'
		];*/

		//(new Notify())->toMail($details);
		//echo "email send";
		return redirect()->route($redirecroute)->with('message', $redirectmessage.' Created!');
	}

	public function update(Request $request){    

		$userType = $request->get('user_hidden');
		$updateId = $request->get('hidden_update_id');
		$validated = $request->validate(
			[
				'first_name' 		=> 'required',
				'telegram_link'	=> 'required_if:user_hidden,==,2',
			],
			[
				'telegram_link.required_if' 			=> 'The telegram link is required when user type is Publishers!',
			]
		); 

		$user = User::findOrFail($updateId);
		$user->first_name = $request->get('first_name');
		$user->last_name = $request->get('last_name');		
		if($userType == 2){			
			$user->telegram_link = $request->get('telegram_link');
			$user->earn_percentage = $request->get('earn_percentage');
		}    	
		$user->save();
		return redirect()->back()->with('message', 'Update Succesfully');
	}


	public function delete($id){
		$idnew = decrypt($id);
		$user = User::findOrFail($idnew);
		$user->delete();
		TelegramGroup::where('publisher_id', decrypt($id))->update([
	      'user_delete' => '1'
	    ]);
		return redirect()->back()->with('message', 'User Deleted!');
	}

}