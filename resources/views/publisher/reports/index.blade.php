@extends('layouts.publisher')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Reports <small>List</small> 
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
					<form action="{{route('publisher.reports.data')}}" method="post">
						@csrf
						<div class="row input-daterange report_list">
							<div class="col-md-12">		
								<div class="d-flex">						
									<div class="report_clndy d-flex">
										<input type="date" name="from_date" class="form-control" placeholder="From Date" value="@if(isset($from_date)){{ $from_date }}@endif" />
										<input type="date" name="to_date" class="form-control" placeholder="To Date" value="@if(isset($to_date)){{ $to_date }}@endif"/>
									</div>
									<div class="filterbtn d-flex">
										<input type="submit" name="filter" class="btn btn-primary" value="Filter">
										<button type="button" class="btn btn-warning">
											<a href="/publisher/reports" class="text-white"> Reset </a>
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<table class="table" id="datatable">
						<thead>
							<tr>
								<th>ID</th>								
								<th>Group Username</th>
								<th>Number Of Ads Shown </th>
								<th>Number Of Clicks </th>							
								<th>Revenue Generated </th>							
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($finalReport as $key => $report)
							<tr>
								<td scope="row">{{ $i }} </td>								
								<td>{{ $report['telegram_group_name'] }}</td>
								<td>{{ $report['no_of_publish'] }}</td>
								<td>{{ $report['no_of_clicks'] }}</td>							
								<td>${{ $report['group_revenue'] }}</td>							
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Report exit!</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>   

@endsection