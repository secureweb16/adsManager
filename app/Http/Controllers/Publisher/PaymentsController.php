<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherPayment;
use Auth;
class PaymentsController extends Controller
{
     public function index()
    {
        $userid =  Auth::user()->id;      
        $publisher = PublisherPayment::where('publisher_id',$userid)->first();   
        return view('publisher.payments.index',compact('publisher'));
    }
}
