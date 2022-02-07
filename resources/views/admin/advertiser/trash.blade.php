@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Trash <small>List</small></h3>
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
								<th>Phone Number</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($onlySoftDeleted as $onlySoftDeleteds)

							<tr>
								<th scope="row">#{{ $i }}</th>
								<td>{{ $onlySoftDeleteds->first_name }} {{ $onlySoftDeleteds->last_name }}</td>
								<td>{{ $onlySoftDeleteds->email }}</td>
								<td>{{ $onlySoftDeleteds->phone_number }}</td>
								<td>							
									@php  $id = encrypt($onlySoftDeleteds->id); @endphp
									<a href="{{route('admin.advertisers.restore',$id)}}" class="btn btn-success btn-sm">Restore</a>
									<form action="{{route('admin.advertisers.destroy',$id)}}" method="post">
										@csrf 
										{{method_field('DELETE')}}
										<button type="submit" class="btn btn-danger btn-sm">Delete Permanent</button>
									</form>
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