@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Publishers <small>List</small> </h3>
		</div>

		<div class="title_right">
			<div class="col-md-4 col-sm-4   form-group pull-right ">
				<div class="input-group">
					<a href="{{URL::to('/admin/publishers/trash')}}">
						<button type="button" class="btn btn-success">Trash</button>
					</a>
					<a href="{{URL::to('/admin/publishers/create')}}">
						<button type="button" class="btn btn-success">Add New</button>
					</a>  
				</div>
			</div>
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
								<th>Publisher Name</th>
								<th>Email</th>
								<th>Total Amount</th>
								<th>Paid Amount</th>
								<th>Payout Balance</th>
								<th>Earn Percentage</th>
								<th>User Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($publishers as $publisher)
							@php $id = encrypt($publisher->id); @endphp
							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $publisher->first_name }} {{ $publisher->last_name }}</td>
								<td>{{ $publisher->email }}</td>
								<td>@if(isset($publisher->reportdata->total_amount)){{ $publisher->reportdata->total_amount }}@endif</td>
								<td>@if(isset($publisher->reportdata->paid_amount)){{ $publisher->reportdata->paid_amount }}@endif</td>
								<td>@if(isset($publisher->reportdata->payable_amount)){{ $publisher->reportdata->payable_amount }}@endif</td>
								<td>@if(isset($publisher->earn_percentage) != ''){{ $publisher->earn_percentage }} @else {{get_option_value('publisher_payout')}}@endif%</td>
								<td> @if($publisher->user_status == 0)
									<a href="javascript:void(0);" class="text-danger">Inactive</a>
									@elseif($publisher->user_status == 1)
									<a href="javascript:void(0);" class="text-success">Active</a>
									@endif
								</td>
								<td>
									<a href="{{route('admin.publishers.show',$id)}}" class="btn btn-success btn-xs">Edit</a>
									<a href="{{ route('admin.user.destroy',$id) }}" class="btn btn-danger btn-xs">Delete</a>
								</td>
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Publisher exit!</td>
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