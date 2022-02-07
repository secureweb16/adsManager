
<!DOCTYPE html>
<html lang="en">
@include('partials.admin.head')

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      @include('partials.admin.sidebar')

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
                  <img src="{{ asset('common/images/130247647.jpg') }}" alt="">{{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                  <a href="{{route('logout')}}" class="dropdown-item"  href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </div>
              </li>
               <li role="presentation" class="nav-item dropdown open">
                    <a href="javascript:void(0);" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-envelope-o"></i>
                      <span class="badge bg-green">{{ count(GetNotificationaAdmin()) }}</span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                    @forelse(GetNotificationaAdmin() as $notification)
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
                        <a href="@if($notification->type == 'campaigns'){{URL::to('/admin/campaigns')}} @else javascript:void(0); @endif" class="dropdown-item" onClick="update_notification_admin({{$notification->id}},'{{$notification->admin_url}}')">
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
                          <span class="message">{{ $notification->admin_message; }}</span>
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
            </ul>
          </nav>
        </div>
      </div>
      <!-- page content -->
      <div class="right_col" role="main">
        @yield('content')
      </div>
    </div>
  </div>
</body> 

@include('partials.admin.footer')
