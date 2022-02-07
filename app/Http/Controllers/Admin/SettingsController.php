<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class SettingsController extends Controller
{
  public function index(){ 
    return view('admin.settings.index'); 
  }

  public function save(Request $request){
    $request->validate([
      'min_CPC_bid'   => 'nullable|numeric|between:0,9999.9999',
    ]);

    $min_CPC =$request->min_CPC_bid;
    update_option_value('average_min_CPC_bid',$min_CPC);
    return redirect()->route('admin.index')->with(['message'=> 'Added Successfully!']);
  }

  public function save_email_view(){ 
    return view('admin.settings.admin-email'); 
  }

  public function save_email(Request $request){
    $adminstrater_email =$request->adminstrater_email;
    update_option_value('adminstrater_email',$adminstrater_email);
    return redirect()->route('admin.settings.email')->with(['message'=> 'Added Successfully!']);
  }

  public function save_hours_view(){ 
    return view('admin.settings.campaign-publish-hrs'); 
  }

  public function save_hours(Request $request){
    $telegram_group_hrs =$request->telegram_group_hrs;
    update_option_value('telegram_group_hrs',$telegram_group_hrs);
    return redirect()->route('admin.settings.hours')->with(['message'=> 'Added Successfully!']);
  }

  public function publisherpay_view(){     
    return view('admin.settings.publisherpay'); 
  }

  public function publisherpay(Request $request){ 
    $request->validate([
      'percentage'   => 'required|numeric|between:0,9999.9999',
    ]);
    $percentage =$request->percentage;
    update_option_value('publisher_payout',$percentage);
    return redirect()->route('admin.settings.publisherpay')->with(['message'=> 'Success!']);   
  }

}
