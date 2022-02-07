<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tier;
use App\Models\TierPublisher;

class TierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
      $alltier = Tier::get();
      return view('admin.tiers.list',compact('alltier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tierPublisher = TierPublisher::pluck('publisher_id')->toArray();
        $allpublisher = User::whereNotIn('id',$tierPublisher)->where('user_role', '=','2')->where('user_status', '=', '1')->get();
        return view('admin.tiers.create',compact('allpublisher'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
      /*echo "<pre>";
      print_r($request->all());      
      echo $data;
      exit;*/
      $validated = $request->validate([
        'tier_name'   => 'required',
        'publisher'   => 'required',
        'minimun_cpc' => 'required|numeric|between:0,9999.9999',
        'payout'      => 'required|numeric|between:0,9999.9999',
      ]);

      $tier   = new  Tier();
      $tier->tier_name         = $request->get('tier_name');
      $tier->tier_description  = $request->get('tier_description');
      $tier->publisher         = '['.implode(',',$request->get('publisher')).']';
      $tier->minimun_cpc       = $request->get('minimun_cpc');
      $tier->payout            = $request->get('payout');
      $tier->save();      
      foreach ($request->get('publisher') as $publisherid) {
        $tierpublisher  = new  TierPublisher();
        $tierpublisher->tier_id       = $tier->id;
        $tierpublisher->publisher_id  = $publisherid;
        $tierpublisher->minimun_cpc   = $request->get('minimun_cpc');
        $tierpublisher->payout        = $request->get('payout');
        $tierpublisher->save();
      }
      return redirect()->back()->with(['success'=>'Tier created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $tierPublisher = TierPublisher::where('tier_id','!=',decrypt($id))->pluck('publisher_id')->toArray();
      $allpublisher = User::whereNotIn('id',$tierPublisher)->where('user_role', '=','2')->where('user_status', '=', '1')->get();
      $tier = Tier::with(['get_publisher'])->where('id',decrypt($id))->first();
      $publisherid = array();
      foreach($tier->get_publisher as $value){
        $publisherid[] = $value->publisher_id;
      }
      return view('admin.tiers.edit',compact('tier','allpublisher','publisherid'));
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
      $validated = $request->validate([
        'tier_name'   => 'required',
        'publisher'   => 'required',
        'minimun_cpc' => 'required|numeric|between:0,9999.9999',
        'payout'      => 'required|numeric|between:0,9999.9999',
      ]);
      $id = decrypt($id);
      $tier   = Tier::find($id);
      $tier->tier_name         = $request->get('tier_name');
      $tier->tier_description  = $request->get('tier_description');
      $tier->publisher         = '['.implode(',',$request->get('publisher')).']';
      $tier->minimun_cpc       = $request->get('minimun_cpc');
      $tier->payout            = $request->get('payout');
      $tier->save();
      TierPublisher::where('tier_id', $id)->delete();
      foreach ($request->get('publisher') as $publisherid) {
        $tierpublisher  = new  TierPublisher();
        $tierpublisher->tier_id       = $tier->id;
        $tierpublisher->publisher_id  = $publisherid;
        $tierpublisher->minimun_cpc   = $request->get('minimun_cpc');
        $tierpublisher->payout        = $request->get('payout');
        $tierpublisher->save();
      }
      return redirect()->back()->with(['success'=>'Tier updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
