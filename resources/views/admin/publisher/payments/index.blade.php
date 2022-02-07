
@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
	<div class="page-title">
		<div class="title_left">
			<h3>Publishers <small>Payments</small> 

			</h3>      
		</div>	
	</div>

	<div class="clearfix"></div>
	<div class="row clearfix" style="display: block;">
		<div class="col-md-12 col-sm-6">
		 	<div class="x_panel">
				<div class="x_content tblcontent">
					<table class="table" id="datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>No. of clicks</th>
								<th>Old clicks</th>
								<th>Paid Amount</th>
								<th>Payable Amount</th>
								<th>Total Amount</th>
								<th>Group Id</th>
								<th>Paid Date</th>
							</tr>
						</thead>
						<tbody>
							@php $i=1; @endphp
							@forelse($publisherpayment as $publisherpayments)
							<tr>
								<th scope="row">{{ $i }}</th>
								<td>{{ $publisherpayments->no_of_clicks }}</td>
								<td>{{ $publisherpayments->old_clicks }}</td>
								<td>{{ $publisherpayments->paid_amount }}</td>
								<td>{{ $publisherpayments->payable_amount }}</td>
								<td>{{ $publisherpayments->total_amount }}</td>
								<td>{{ $publisherpayments->group_id }}</td>
								<td>{{ $publisherpayments->paid_date }}</td>								
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