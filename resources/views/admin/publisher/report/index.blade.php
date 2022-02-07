
@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Publishers <small>Reports</small> 

			</h3>      
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
								<th>No. of publish</th>
								<th>No. of view</th>
								<th>No. of clicks</th>
								<th>Average CPC</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($publisherpayment as $publisherpayments)

							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $publisherpayments['campaign_name'] }}</td>
								<td>{{ $publisherpayments['no_of_publish'] }}</td>
								<td>{{ $publisherpayments['no_of_views'] }}</td>
								<td>{{ $publisherpaymentspublisherpayments['no_of_clicks'] }}</td>
								<td>{{ $publisherpayments['average_cpc'] }}</td>
	
								<td></td>
								<td></td>
								
							</tr>
							@php $i++ @endphp
							@empty
							<tr>
								<td>No Publisher exit!</td>
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