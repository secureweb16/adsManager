@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Advertiser <small>List</small></h3>
		</div>

		<div class="title_right">
			<div class="col-md-4 col-sm-4 form-group pull-right ">
				<div class="input-group">
					<a href="{{URL::to('/admin/advertisers/trash')}}">
						<button type="button" class="btn btn-danger btn-sm">Trash</button>
					</a> 
					<a href="{{URL::to('/admin/advertisers/create')}}">
						<button type="button" class="btn btn-primary btn-sm">Add New</button>
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
								<th>Advertiser Name</th>
								<th>Email</th>
								<th>User Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($advertisers as $advertiser)
							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $advertiser->first_name }} {{ $advertiser->last_name }}</td>
								<td>{{ $advertiser->email }}</td>
								<td> 
									@if($advertiser->user_status == 0) 
									<a href="javascript:void(0);" class="btn btn-primary btn-sm">InActive</a>
									@elseif($advertiser->user_status == 1) 
									<a href="javascript:void(0);" class="btn btn-success btn-sm">Active</a>
									@endif
								</td>
								
								<td>
									@php  $id = encrypt($advertiser->id); @endphp
									<a href="{{route('admin.advertisers.campaigns',$id)}}" class="btn btn-success btn-sm">Campaigns</a>
									<a href="{{route('admin.advertisers.show',$id)}}" class="btn btn-success btn-sm">Edit</a>
									<a href="{{ route('admin.user.destroy',$id) }}" class="btn btn-danger btn-sm">Delete</a>
								</td>
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Advertiser exit!</td>
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