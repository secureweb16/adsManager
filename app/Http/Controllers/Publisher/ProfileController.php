<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $userid =  Auth::user()->id;
      $publisher = User::where('id',$userid)->first();      
      return view('publisher.profile.index',compact('publisher'));
    }


    public function store(Request $request)
    {       
      $user_id = Auth::user()->id;
      $userdata = User::findOrFail($user_id);
      $userdata->first_name = $request->get('first_name');
      $userdata->last_name = $request->get('last_name');      
      $userdata->telegram_link  = $request->get('telegram_link');
      $userdata->save();
      return redirect()->back()->with('message', 'Profile Update Succesfully');
    }

}
