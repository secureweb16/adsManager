@extends('layouts.advertiser')
@section('content')
<div class="rgtouterwrap">
  <div class="page-title">
    <div class="title_left">
      <h3>Funds <small>List</small> 
       
      </h3>      
    </div>

    <div class="title_right">
      <div class="col-md-3 col-sm-3   form-group pull-right ">
        <div class="input-group">
          <a href="{{URL::to('/advertiser/funds/create')}}">
            <button type="button" class="btn btn-primary btn-sm">Add New</button>
          </a>  
        </div>
      </div>
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
                <th>Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($fundsAdviser as $fundsdviser)
              <tr>                
                <td>{{ $i }}</td>
                <td>{{ $fundsdviser->buyer_name }}</td>
                <td>{{ $fundsdviser->buyer_email }}</td>
                <td>
                @php 
                  $fundslastupdate = array();
                  $payment_status = 'Waiting for buyer funds..';
                  $total = count($fundsdviser->payment_update);
                @endphp 

                @if($total > 0)
                  @php 
                    $fundslastupdate = $fundsdviser->payment_update[$total-1];
                    $curency_price = $curencyprice[$fundslastupdate->currency2];
                    $price_usd       = $curency_price*$fundslastupdate->received_amount;
                    $payment_status  = $fundslastupdate->status_text;
                  @endphp                 
                @endif
                
                $@if($payment_status == 'Complete') {{ number_format($price_usd,2) }} @else 0.00 @endif
                
                </td>
                <td>{{ $payment_status }}</td>
              </tr>
              @php $i++ @endphp
              @empty
              <tr>
                <td>No Funds exit!</td>
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