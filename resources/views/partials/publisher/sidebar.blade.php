<div class="col-md-3 left_col dashbrd_lftmenu">
	<div class="left_col scroll-view">
		<div class="navbar nav_title" style="border: 0;">
			<a href="{{URL::to('/publisher/dashboard')}}" class="site_title">
				<img src="{{ asset('common/images/LOGOBLUE.png') }}"></a>
			</div>

			<div class="clearfix"></div>
			<!-- sidebar menu -->
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
				<div class="menu_section">                
					<ul class="nav side-menu">                  
						<li>
							<a href="{{URL::to('/publisher/dashboard')}}">
								<i class="fa fa-columns"></i> Dashboard <span class="fa fa-chevron-down"></span>
							</a>
						</li>					
						
						<li><a><i class="fa fa-columns"></i> Management <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="{{URL::to('/publisher/reports')}}">Report</a></li>			       
							</ul>
						</li>

						<li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="{{URL::to('/publisher/settings')}}">Payments</a></li>
								<li><a href="{{URL::to('/publisher/settings/telegram-group')}}">Telegram Groups</a></li>			       
							</ul>
						</li>                  
					</ul>
				</div>
			</div>
			<!-- /sidebar menu -->

			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu sidebarbtmmenu">
				<ul class="nav side-menu">
					<li><a href="javascript:void(0);"> Get Support </a></li>
					<li>
						<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top">          
							<img src="{{ asset('common/images/telegram-logo.png') }}" alt="Telegram Logo" width="50px">
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="email_poplink"> Email Us </a>
					</li>
				</ul>
			</div>

			<!-- /menu footer buttons -->
			<div class="sidebar-footer hidden-small">
				<a href="{{route('logout')}}" data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
					<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
				</a>
			</div>
			<!-- /menu footer buttons -->
		</div>
	</div>

	<script type="text/javascript">

		$(document).ready(function(){
			$('.email_poplink').click(function(){
				$('.view-poup').addClass('showpoup');
			});
			$('.popclose').click(function(){
				$('.view-poup').removeClass('showpoup');
			});
		});

	</script>