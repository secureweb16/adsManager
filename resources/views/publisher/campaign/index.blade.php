@extends('layouts.publisher')
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
		<div class="col-md-12 col-sm-6  ">

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
								<th>Budget</th>
								<th>Landing url</th>
								<th>Spent Type </th>
								<th>Balance </th>
								<th>Status</th>
								
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($publisherCampaigns as $campaigns)

							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $campaigns->getPublisherCampaign->campaign_name }}</td>

								<td>{{ $campaigns->getPublisherCampaign->budget }}</td>

								<td>{{ $campaigns->getPublisherCampaign->landing_url }}</td>

								<td> @if($campaigns->getPublisherCampaign->spend_type == 0 )  Daily 
								@elseif($campaigns->getPublisherCampaign->spend_type == 1) 
								PPC
							    @endif </td>

   								<td></td>
								<td> @if($campaigns->getPublisherCampaign->campaign_status == 0) 
									<a href="javascript:void(0);" class="btn btn-primary btn-xs">Pause</a>
								@elseif($campaigns->getPublisherCampaign->campaign_status == 1) 
									<a href="javascript:void(0);" class="btn btn-primary btn-xs">Active</a>
								@elseif($campaigns->getPublisherCampaign->campaign_status == 2) 
									<a href="javascript:void(0);" class="btn btn-primary btn-xs">Stop</a>
							    @endif </td>
								
								
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Campaigns exit!</td>
							</tr>
							@endforelse
						</tbody>
					</table>

					<div class="view-poup">
						<div class="image">
							<img src="">
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>   

@endsection