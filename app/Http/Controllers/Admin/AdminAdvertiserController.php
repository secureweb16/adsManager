<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\AdminAddFunds;
use App\Models\FundsDetails;
use App\Models\Campaign;

class AdminAdvertiserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$advertisers = $this->get_all_advertiser();
    	return view('admin.advertiser.list', compact('advertisers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('admin.advertiser.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$id = decrypt($id);
    	$advertisers = User::findOrFail($id);
    	return view('admin.advertiser.edit', compact('advertisers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$id = decrypt($id);
    	$data=User::onlyTrashed()->find($id);
    	$data->forceDelete();
    	return redirect()->back()->with(['message'=> 'Deleted Successfully!']);
    }
    public function trash()
    {
    	$onlySoftDeleted = User::onlyTrashed()
    	->where('user_role','3')
    	->get();
    	return view('admin.advertiser.trash', compact('onlySoftDeleted'));
    }
    public function restore($id)
    {
    	$id = decrypt($id);
    	$data=User::withTrashed()->find($id)->restore();
    	return redirect()->back()->with(['message'=> 'Restored Successfully!']);
    }

    public function funds_view()
    {

    	$advertisers = $this->get_all_advertiser();

    	return view('admin.advertiser.addfunds', compact('advertisers'));

    }

    public function funds_add(Request $request)
    {

     $request->validate([
      'advertiser'  => 'required',
      'amount'   		=> 'required|numeric|between:0,9999.9999',      
    ]);

     $adminaddfunds = new AdminAddFunds();
     $adminaddfunds->user_id = $request->get('advertiser');
     $adminaddfunds->amount 	= $request->get('amount');
     $adminaddfunds->save();
     $fundsdetails = FundsDetails::where('user_id',$request->get('advertiser'))->first();

     if(empty($fundsdetails)){
      $fundsDetails = new FundsDetails();
      $fundsDetails->user_id        = $request->get('advertiser');
      $fundsDetails->total_funds    = $request->get('amount');
      $fundsDetails->remaning_funds = $request->get('amount');
      $fundsDetails->save();
    }else{
      $remaingfunds = $fundsdetails->remaning_funds+$request->get('amount');
      $totalfunds = $fundsdetails->total_funds+$request->get('amount');
      FundsDetails::where('user_id',$request->get('advertiser'))
      ->update(['remaning_funds' => $remaingfunds,'total_funds'=>$totalfunds]);
    }

    return redirect()->route('admin.advertisers.funds')->with(['message'=> 'Funds Add Successfully!']);

  }

  public function get_all_advertiser()
  {

    return User::where('user_role','3')->get();

  }

  public function campaigns($id)
  { 
    $id = decrypt($id);

    $campaign = Campaign::where('advertiser_id',$id)->get();

    return view('admin.advertiser.campaigns.index', compact('campaign'));
  }

}
