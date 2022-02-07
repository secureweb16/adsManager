<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TelegramGroup;
use Auth;
use URL;

class CheckPublisherTelegram
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */

  public function handle(Request $request, Closure $next)
  {   
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') { $protocol = 'https://'; }
    else {  $protocol = 'http://'; }
    
    $groups = TelegramGroup::where('publisher_id','=',Auth::user()->id)->first();    
    if((empty($groups) &&  URL::current() == $protocol.'adsmanager.moonlaunch.media/publisher/settings/telegram-add') || URL::current() == $protocol.'adsmanager.moonlaunch.media/publisher/settings/telegram-group' || URL::current() == $protocol.'adsmanager.moonlaunch.media/publisher/bot-setup'){
       return $next($request);
    }elseif(!empty($groups)){
      return $next($request);
    }else{
      return redirect()->route('publisher.settings.telegram.add');
    }
  }
}
