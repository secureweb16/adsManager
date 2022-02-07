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

    Cancle

    </div>
  </div>
</div>   

@endsection