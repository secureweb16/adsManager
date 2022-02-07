@extends('layouts.admin')
@section('content')

<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Publisher <small>Report</small> 
			</h3>      
		</div>
	</div>
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
								<th> ID </th>
								<th> Name </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($allReport as $report)
							<tr>
								<th scope="row"> {{ $i }} </th>
								<td> {{ $report->csv_name }} </td>
								<td>
									<a href="{{ route('csv.download', encrypt($report->id)) }}" class="btn btn-primary btn-sm"> Download </a>
									@if($report->mark_paid == '0')
										<a href="{{ route('admin.markpaid', encrypt($report->id)) }}" class="btn btn-primary btn-sm not"> Mark Paid </a>	
									@else
										<a href="javascript:void(0);" class="btn btn-primary btn-sm paid"> Paid </a>	
									@endif
								</td>
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No reports are avaliable!</td>
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