@extends('layouts.publisher')
@section('content')

<!-- top tiles -->
<div class="dashboarddiv" style="display: inline-block;" >
	<div class="tile_count">
    <div class="row">
      <div class="col-md-12">
        <form method="get">          
          <div class="row input-daterange">
            <div class="col-md-12">
              <div class="d-flex">
                <div class="selectdate">
                   <input name="daterange" id="pubreportrange" type="text" class="form-control" readonly="" value="<?php echo $feildVal; ?>">
                  <!--   <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i> -->
                </div>
                <div class="filterbtn d-flex">
                  <input type="submit" id="submit123" name="filter" class="btn btn-primary" value="Filter">
                  <button type="button" class="btn btn-warning">
                    <a href="/publisher/dashboard" class="text-white"> Reset </a>
                  </button>
                </div>
              </div>
            </div>

            <!-- <div class="col-md-4">                
              <input type="date" name="from_date" class="form-control" placeholder="From Date" onchange="onChangeDate();"  id ="toFrom" value="@if(isset($_GET['from_date'])){{ $_GET['from_date'] }}@endif" />
            </div>
            <div class="col-md-4">
              <input type="date" name="to_date" id="toDate" class="form-control" onchange="onChangeDate();" placeholder="To Date" value="@if(isset($_GET['to_date'])){{ $_GET['to_date'] }}@endif"/>
            </div> -->
            <div class="col-md-4">
              
            </div>
          </div>
        </form>
      </div>
      <span id="errordate" style="color:red;"></span>
    </div>

    <div class="dashboxes">
    <div class="row">      
      <div class="col-md-3 col-sm-4">
        <div class="tile_count_inr">
          <span class="count_top"><i class="fa fa-money"></i> Earnings </span>
          <div class="count orange">${{$totalAmount}}</div>
        </div>
      </div> 

      <div class="col-md-3 col-sm-4">
        <div class="tile_count_inr">
          <span class="count_top"><i class="fa fa-mouse-pointer"></i> Clicks </span>
          <div class="count orange">{{$noOfClicks}}</div>
        </div>
      </div> 

      <div class="col-md-3 col-sm-4">
        <div class="tile_count_inr">
          <span class="count_top"><i class="fa fa-tachometer"></i> Average CPC </span>
          <div class="count orange">${{$averageCPC}}</div>
        </div>
      </div>  

      <div class="col-md-3 col-sm-4">
        <div class="tile_count_inr">
          <span class="count_top"><i class="fa fa-clipboard"></i> Posts Shown </span>
          <div class="count orange">{{$noOfPublish}}</div>
        </div>
      </div>  

      <div class="col-md-3 col-sm-4">
        <div class="tile_count_inr">
          <span class="count_top"><i class="fa fa-life-ring"></i> Average Earnings Per Post </span>
          <div class="count orange">${{get_option_value('average_cost_value')}}</div>
        </div>
      </div>      

    </div>
  </div>
  </div>
</div>
<script type="text/javascript">
const input = document.getElementById('pubreportrange');
input.addEventListener('change', updateValue);
function updateValue(e) {
  console.log('e.target.value',e.target.value);
}

</script>
@endsection