@extends('layouts.advertiser')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Add Fund</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="x_panel">
        <div class="x_title">
          <h4><small>* Fields are required:</small></h4>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />

          <form id="demo-form" method="post" enctype=multipart/form-data  data-parsley-validate class="form-horizontal form-label-left" action="{{route('advertiser.funds.store')}}">

            @csrf

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" > Funds Amount <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 add-doller">
                <input type="number" value="{{old('amount')}}"  name="amount"  class="form-control" required>
                @if ($errors->has('amount')) 
                <div class="error-custom"> {{$errors->first('amount') }} </div>
                @endif
              </div>
            </div>
            
     <!--        <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" > Description <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" value="{{old('description')}}"  name="description"  class="form-control">
                @if ($errors->has('description')) 
                <div class="error-custom"> {{$errors->first('description') }} </div>
                @endif
              </div>
            </div> -->

            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <!-- <button class="btn btn-primary" type="button">Cancel </button> -->
                <button type="submit" id="submit" class="btn btn-success btn-sm">Submit</button>
                <button class="btn btn-primary btn-sm" type="reset">Reset</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
</div>
<script>
  CKEDITOR.replace( 'description' );
</script>
@endsection
