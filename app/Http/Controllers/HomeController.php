<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use Auth;
use App\Models\PublisherReportCsv;
use App\Models\User;

class HomeController extends Controller
{

  private $data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {

      if (!empty(Auth::user())) {

        if (Auth::user()->user_role == '1') {
          return redirect('/admin/dashboard?daterange='.date('m/d/Y').' - '.date('m/d/Y'));
        } elseif (Auth::user()->user_role == '2') {
          return redirect('/publisher/dashboard?daterange='.date('m/d/Y').' - '.date('m/d/Y'));
        }elseif (Auth::user()->user_role == '3') {            
          return redirect('/advertiser/dashboard?daterange='.date('m/d/Y').' - '.date('m/d/Y'));
        }
      }
    }

    public function logout()
    {
      Auth::logout();
      return redirect('/');
    }
    public function logoutauto()
    {
      Auth::logout();
      echo "<script>window.close()</script>";
    }

     /**
     * Download the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function download($uuid)
     {
      if (!empty(Auth::user())) {
        $csv = PublisherReportCsv::where('id', decrypt($uuid))->firstOrFail();
        $this->data = $csv;
        if (Auth::user()->user_role == '1') {          
          return $this->downloadPdf();          
        } else {
          abort(404);
        }
      }
      abort(404);
    }



    protected function downloadPdf()
    {
      $csv = $this->data;
      $pathToFile = url('/publisherReport');//storage_path('app/public/publisherReport/' . $csv->csv_name);
      $pathToFile = $pathToFile.'/'.$csv->csv_name;
      // return response()->file($pathToFile);
      header('Content-Type: application/octet-stream');  
      header("Content-Transfer-Encoding: utf-8");   
      header("Content-disposition: attachment; filename=\"" . basename($pathToFile) . "\"");   
      readfile($pathToFile);  
    }

  }
