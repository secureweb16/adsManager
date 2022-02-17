<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\PublisherReport;
use App\Models\PublisherPayment;
use App\Models\TelegramGroup;
use App\Models\TelegramTiming;

class AdminPublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $publishers = User::with(['reportdata'])->where('user_role','2')->get();
      return view('admin.publisher.list', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.publisher.add');
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
      $publishers = User::findOrFail($id);     
      return view('admin.publisher.edit', compact('publishers'));
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
     $group_id = TelegramGroup::where('publisher_id',$id)->pluck('id')->toArray();
     $data->forceDelete();
     TelegramTiming::whereIn('telegram_group_id', $group_id)->delete();
     return redirect()->back()->with(['message'=> 'Deleted Successfully!']);
   }
   
   public function trash()
   {
     $onlySoftDeleted = User::onlyTrashed()
     ->where('user_role','2')
     ->get();
     return view('admin.publisher.trash', compact('onlySoftDeleted'));
   }
   
   public function restore($id)
   {
     $id = decrypt($id);
     $data=User::onlyTrashed()->find($id)->restore();
     TelegramGroup::where('publisher_id', $id)->update([
      'user_delete' => '0'
    ]);
     return redirect()->back()->with(['message'=> 'Restored Successfully!']);
   }

   public function reports($id) {
    $id = decrypt($id);
    $publisherreport = PublisherReport::where('publisher_id',$id)->get()->groupBy('campaign_id');
    $finalReport = array();
    $no_of_publish = $no_of_views = $no_of_clicks = $average_cpc = 0;
    foreach ($publisherreport as $campaign_id => $publisherReposrt) {
      $i = 1;
      foreach ($publisherReposrt as $value) {
        $no_of_publish += $value->no_of_publish;
        $no_of_views += $value->no_of_views;
        $no_of_clicks += $value->no_of_clicks;
        $average_cpc += $value->average_cpc;
        $i++;
      }

      $finalReport[] =array(
        'campaign_name' => get_campaign_name($campaign_id),
        'no_of_publish' => $no_of_publish,
        'no_of_views'   => $no_of_views,
        'no_of_clicks'  => $no_of_clicks,
        'average_cpc'   => ($average_cpc/$i)        
      );
    }
    return view('admin.publisher.report.index', compact('finalReport'));
  }

  public function payments($id)
  { 
    $id = decrypt($id);    
    $publisherpayment = PublisherPayment::where('publisher_id',$id)->first();        
    return view('admin.publisher.payments.index', compact('publisherpayment'));
  }

  public function all_groups(){
    $publishers = User::with(['reportdata'])->where('user_role','2')->get();
    $groups = TelegramGroup::withSum('groups_earnings','payable_amount')->where('user_delete','0')->get();    
    return view('admin.publisher.telegram.list',compact('groups','publishers'));
  }

  public function group_status_update(Request $request){
    TelegramGroup::where('id', $request->get('telegrm_group'))->update([
      $request->get('key') => $request->get('value')
    ]);
  }

  public function groups_delete($id){    
    $telegramgroup = TelegramGroup::find(decrypt($id));
    $telegramgroup->delete();
    TelegramTiming::where('telegram_group_id', decrypt($id))->delete();
    return redirect()->back()->with('message', 'Group Deleted!');
  }

}
