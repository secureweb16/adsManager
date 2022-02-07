@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Campaign <small>List</small> 
			</h3>      
		</div>
	</div>

	<div class="clearfix"></div>

	<div class="row clearfix" style="display: block;">
		<div class="col-md-12 col-sm-6">
			@if(Session::get('message'))
			<div class="alert alert-success alert-dismissible " role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<strong>Success!</strong> {{ Session::get('message') }}
			</div>
			@endif
			<div class="x_panel">
				<div class="x_content tblcontent">
					<table class="table" id="datatable">
						<thead>
							<tr>
								<th>ID</th>								
								<th>Campaign Name</th>
								<th>Campaign Budget</th>
								<th>Remaing Budget</th>
                <th>PPC</th>
                <th>Daily</th>
                <th>Remaing Daily</th>
								<th>Landing Url</th>
								<th>Admin Status</th>
								<th>campaign Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($campaigns as $campaign)
							<tr>
								<th scope="row">{{ $i }}</th>								
								<td>{{ $campaign->campaign_name }}</td>
								<td>${{ $campaign->campaign_budget }}</td>
								<td>${{ $campaign->remaing_total }}</td>
                <td> ${{ $campaign->pay_ppc }} </td>
                <td> ${{ $campaign->pay_daily }} </td>
                <td> ${{ $campaign->remaing_daily }} </td>
								<td>{{ $campaign->landing_url }}</td>
								<td>									
									@if($campaign->admin_approval == 0) <span class="text-danger" style="pointer-events: none;">Pending</span>
									@elseif($campaign->admin_approval == 1) <span class="text-success" style="pointer-events: none;">Approved</span>
									@elseif($campaign->admin_approval == 2) <span class="text-danger" style="pointer-events: none;">Declined</span>
									@endif									
								</td>
								<td>									
									@if($campaign->campaign_status == 0)<span class="text-danger">Pause</span>
									@elseif($campaign->campaign_status == 1)<span class="text-warning">Running</span> 
									@elseif($campaign->campaign_status == 2)<span class="text-danger">Stop</span>									
									@endif									
								</td>

								<td>							
									<a href="javascript:void(0);" class="btn btn-primary btn-sm" onClick="campaign_view({{ $campaign->id }})">View</a>
									@if($campaign->admin_approval == 0)
									<a href="javascript:void(0);" class="btn btn-success btn-sm" onClick="change_campaign_status({{ $campaign->id }},1)" >Approve</a>
									<a href="javascript:void(0);" class="btn btn-danger btn-sm" onClick="change_campaign_status({{ $campaign->id }},2)">Decline</a>
									@endif
								</td>
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Campaigns exit!</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>   
<div class="view-poup">
	<div class="popup_wrap">
		<span class="popclose">X</span>
		<div class="inner-div">
			<div class="profcover">
				<img src="" id="bannerimage">
			</div>
		</div>
		<div class="inner-div">
			<ul>
				<li><span>Status:</span><div class="adver_name" id="adver_name"></div>
					<button id="admin_approval"></button>
				</li>
				<li><span>Budget: </span><div class="budget" id="budget"></div></li>
				<li><span>Campaign Name: </span><div class="campaign_name" id="campaign_name"></div></li>
				<li><span>Description:</span><div class="description" id="description"></div></li>
				<li><span>Landing Url:</span><div class="landing_url" id="landing_url"></div></li>
				<li><span>Spend type :</span><div id="spend_type"></button></li>					
					<li><span>Pay PPC or Daily </span><div class="pay_charges" id="pay_charges"></div></li>
				</ul>
			</div>
		</div>
	</div>
	@endsection