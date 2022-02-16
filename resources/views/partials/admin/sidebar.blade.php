<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{URL::to('/admin/dashboard')}}" class="site_title">
        <img src="{{ asset('common/images/LOGOBLUE.png') }}"></a>
      </div>
      <div class="clearfix"></div>
      <br />
      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <ul class="nav side-menu">
            <li><a href="{{URL::to('/admin/dashboard')}}"><i class="fa fa-columns"></i> Dashboard <span class="fa fa-chevron-down"></span></a></li>
            
            <li><a><i class="fa fa-columns"></i> Tiers <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{route('admin.tiers.index')}}"> All Tiers </a></li>
                <li><a href="{{route('admin.tiers.create')}}"> Add New </a></li>
                <!-- <li><a href="{{route('admin.tiers.report')}}"> Tier Report </a></li> -->
              </ul>
            </li>

            <li><a><i class="fa fa-columns"></i> Campiagins <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{URL::to('/admin/campaigns')}}">All Campaigns </a></li>
                <li><a href="{{URL::to('/admin/campaigns/approvedlist')}}"> Approved Campigns</a></li>
                <li><a href="{{URL::to('/admin/campaigns/pendinglist')}}"> Pending Campigns</a></li>
              </ul>
            </li>

            <li><a><i class="fa fa-columns"></i> Advertiser <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{URL::to('/admin/advertisers')}}"> All Advertiser </a></li>
                <li><a href="{{URL::to('/admin/advertisers/funds')}}"> Add Advertiser Funds </a></li>
              </ul>
            </li>

            <li><a><i class="fa fa-columns"></i> Publisher <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{URL::to('/admin/publishers')}}"> All Publisher </a></li>
                <li><a href="{{route('admin.publishers.groups')}}"> All Groups </a></li>
              </ul>
            </li>

            <li><a><i class="fa fa-columns"></i> Export/Import <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{URL::to('/admin/export')}}"> Export </a></li>
                <li><a href=" {{URL::to('/admin/import')}}"> Import </a></li>
              </ul>
            </li>
            <li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="{{URL::to('/admin/settings')}}"> Min CPC Bid </a></li>
                <li><a href="{{URL::to('/admin/settings/publisherpay')}}"> Publisher Payout </a></li>
                <li><a href="{{URL::to('/admin/settings/email')}}"> Adminstrater Email </a></li>
                <!-- <li><a href="{{URL::to('/admin/settings/hours')}}"> Telegram group HRS </a></li> -->
              </ul>
            </li>
        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
     
      <a href="{{route('logout')}}" data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>