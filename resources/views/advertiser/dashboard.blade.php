@extends('layouts.advertiser')
@section('content')

<!-- top tiles -->
<div class="dashboarddiv" style="display: inline-block;" >
	<div class="tile_count">
    <div class="row" >                
     <div class="col-md-12">
      <form method="get" id="mydateform">          
        <div class="row input-daterange">
          <div class="col-md-12">
            <div class="d-flex">
              <div class="selectdate">
                <input name="daterange" id="advreportrange" type="text" class="form-control" readonly="" value="<?php echo $feildVal; ?>">
              </div>
              <div class="filterbtn d-flex">
                <input type="submit" name="filter" id="submit" class="btn btn-primary" value="Filter">
                <button type="button" class="btn btn-warning">
                  <a href="/advertiser/dashboard" class="text-white"> Reset </a>
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <span id="errordate" style="color:red;"></span>
  </div>
</div>

<div class="row">                
  <div class="col-md-3 col-sm-4">
    <div class="tile_count_inr">
      <span class="count_top"><i class="fa fa-user"></i> Funds Balance</span>
      <div class="count orange">${{ $alldata['remaning_funds']}}</div>
    </div>
  </div> 

  <div class="col-md-3 col-sm-4">
    <div class="tile_count_inr">
      <span class="count_top"><i class="fa fa-user"></i> Campaigns Live </span>
      <div class="count orange">{{ $alldata['campaign_active']}}</div>
    </div>
  </div>  

  <div class="col-md-3 col-sm-4">
    <div class="tile_count_inr">
      <span class="count_top"><i class="fa fa-user"></i> Campaigns in Review</span>
      <div class="count orange">{{ $alldata['campaign_inactive']}}</div>
    </div>
  </div>

  <div class="col-md-3 col-sm-4">
    <div class="tile_count_inr">
      <span class="count_top"><i class="fa fa-user"></i> Total Clicks Received </span>
      <div class="count orange">{{$noOfClicks}}</div>
    </div>
  </div>

  <div class="col-md-3 col-sm-4">
    <div class="tile_count_inr">
      <span class="count_top"><i class="fa fa-user"></i> Average CPC ( Cost Per Click ) </span>
      <div class="count orange">$@if(get_option_value('average_cost_value') != ''){{ get_option_value('average_cost_value') }}@else 0 @endif</div>
    </div>
  </div>

</div>
</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $(".icntotlrcvddate").click(function(){
      $('.inrtotlrcvddatebox').css("opacity","1");
    });
  });  
</script>
@endsection