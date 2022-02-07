@extends('layouts.publisher')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Payments <small>List</small> 
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
								<th>NO. of Clicks</th>
								<th>Old Clicks</th>
								<th>Paid Amount </th>
								<th>Payable Amount</th>								
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
						    @if(!empty($publisher))
							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $publisher->campaigndata->campaign_name }}</td>
								<td>{{ $publisher->no_of_clicks }}</td>
								<td>{{ $publisher->old_clicks }}</td>
								<td>{{ $publisher->paid_amount }}</td>
								<td>{{ $publisher->payable_amount }}</td>
							</tr>
							@php $i++ @endphp
						    @else
							<tr>
								<td>No Payments exit!</td>
							</tr>
							@endif
						</tbody>
					</table>					
				</div>
			</div>
		</div>
	</div>
</div>   

@endsection