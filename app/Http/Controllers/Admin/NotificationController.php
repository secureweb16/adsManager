<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
    	// print_r($request->notficationid);
    	$notification = Notification::find($request->notficationid);    	
    	$notification->status = 1;
    	$notification->save();

    }
}
