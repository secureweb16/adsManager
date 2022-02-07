@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Advertiser <small>Campaigns</small></h3>
		</div>
	</div>

	<div class="clearfix"></div>

	<div class="row clearfix" style="display: block;">
		<div class="col-md-12 col-sm-6  ">
			<div class="x_panel">
				<div class="x_content tblcontent">

					<table class="table" id="datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Campaign Name</th>
								<th>Budget</th>
								<th>Spend Type</th>
								<th>Pay Charges</th>
								<th>Landing Url</th>
								<th>Created By</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($campaign as $campaigns)

							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $campaigns->campaign_name }}</td>
								<td>{{ $campaigns->budget }}</td>
								<td> @if($campaigns->spend_type == 1) PPC @elseif($campaigns->spend_type == 2) Daily @endif</td>
								<td>{{ $campaigns->pay_charges }}</td>
								<td>{{ $campaigns->landing_url }}</td>
								<td>{{ $campaigns->created_by }}</td>
								<td>@if($campaigns->campaign_status == 0)<button type="button" class="btn btn-dark btn-sm">Pause</button>
									@elseif($campaigns->campaign_status == 1)<button type="button" class="btn btn-primary btn-sm">Running</button> 
									@elseif($campaigns->campaign_status == 2)<button type="button" class="btn btn-danger btn-sm">Stop</button>									
								@endif</td>
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