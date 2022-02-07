<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;

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
      $advertiser = User::where('id',$userid)->first();
      return view('advertiser.profile.index',compact('advertiser'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {  

      $user_id = Auth::user()->id;

      $userdata = User::findOrFail($user_id);

      $userdata->first_name     = $request->get('first_name');
      $userdata->last_name      = $request->get('last_name');
      // $userdata->email          = $request->get('email');
      $userdata->telegram_link  = $request->get('telegram_link');
      $userdata->save();
      return redirect()->back()->with('message', 'Profile Update Succesfully');

    }    


  }
