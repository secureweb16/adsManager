<!DOCTYPE html>
<html lang="en">
@include('partials.advertiser.head')
<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      @include('partials.advertiser.sidebar')
      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>

          <nav class="nav navbar-nav">
            <ul class=" navbar-right">
          
              <li class="nav-item dropdown open" style="padding-left: 15px;">
                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </a>
                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item"  href="{{route('advertiser.profile.index')}}"><i class="fa pull-right"></i> Profile</a>                  
                  <a class="dropdown-item logoutsite"  href="{{route('logout')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </div>
              </li>
              <li role="presentation" class="nav-item dropdown open">
                <a href="javascript:void(0);" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-envelope-o"></i>
                  <span class="badge bg-green">{{ count(GetNotificationaUser()) }}</span>
                </a>
                <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                  @forelse(GetNotificationaUser() as $notification)
                      @php
                        $date1 = $notification->created_at;
                        $date2 = date("Y-m-d H:i:s");
                        $difference = $date1->diff($date2);                        
                        $diffInSeconds = $difference->s;
                        $diffInMinutes = $difference->i; 
                        $diffInHours   = $difference->h;
                        $diffInDays    = $difference->d;
                        $diffInMonths  = $difference->m;
                        $diffInYears   = $difference->y;
                      @endphp

                      <li class="nav-item" data-notfication="{{ $notification->id }}">
                        <a href="@if($notification->type == 'campaigns'){{URL::to('/admin/campaigns')}} @else javascript:void(0); @endif" class="dropdown-item" onClick="update_notification_user({{$notification->id}},'{{$notification->url}}')">
                          <!-- <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span> -->
                          <span>                            
                            <span class="time">
                              @if($diffInYears != 0)   {{ $diffInYears }} year ago
                              @elseif($diffInMonths != 0)  {{ $diffInMonths }} months ago
                              @elseif($diffInDays  != 0)   {{ $diffInDays }} days ago
                              @else
                                @if($diffInHours != 0)   {{ $diffInHours }} hour @endif
                                @if($diffInMinutes != 0) {{ $diffInMinutes }} minutes  ago @endif
                                @if($diffInMinutes == 0) {{ $diffInSeconds }} seconds  ago @endif
                              @endif                               
                            </span>
                          </span>
                          <span class="message">{{ $notification->message; }}</span>
                        </a>
                      </li>
                      @empty
                      <li class="nav-item">
                        <a class="dropdown-item">                          
                          <span class="message">
                            No notification exit!
                          </span>
                        </a>
                      </li>
                      @endforelse
                </ul>
              </li>
              <li class="btn btn-primary btn-sm"> <a href="{{URL::to('/advertiser/funds/create')}}">Add Funds </a></li>
              <li>Funds Balance: ${{ get_total_funds(Auth::user()->id) }} </li>

              @if(Auth::user()->id)
              <li> 
                <form role="login" method="post" target="_blank" action="https://marketplace.moonlaunch.media/autologin">
                    <input type="hidden" name="email_address" value="{{Auth::user()->email}}" />
                    <input type="hidden" name="first_name" value="{{Auth::user()->first_name}}" />
                    <input type="hidden" name="last_name" value="{{Auth::user()->last_name}}" />
                    <input type="hidden" name="password" value="{{Auth::user()->password}}" />
                    <input type="hidden" name="user_role" value="1" />
                    <input type="hidden" id="Marketplacecountryname" name="countryname" value="" />
                    <input type="hidden" id="uniquekey" name="uniquekey" value="{{base64_encode(hash_hmac('sha256', Auth::user()->id.'auth@123#','08f2ff7cf5c59AdManager'))}}" />                      
                    <input type="submit" class="btn btn-primary autologin"  name="login" value="Marketplace" />
                </form>
              </li>
              @endif

            </ul>
          </nav>
        </div>
      </div>
      <!-- page content -->
      <div class="right_col" role="main">
        @yield('content')
        @include('partials.advertiser.popup')
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('body').on('click', '.logoutsite', function() {          
      window.open("https://marketplace.moonlaunch.media/logoutauto");
    });
</script>
</body>

@include('partials.advertiser.footer')
