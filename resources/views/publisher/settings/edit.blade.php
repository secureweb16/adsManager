@extends('layouts.publisher')
@section('content')

<!-- @php 
print_r($account);
@endphp -->
<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Edit Account</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>* Fields are required:</small></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          @if ($errors->any())
          <div class="error-custom">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif 
           @php $id = encrypt($account->id);
            @endphp
          <form id="demo-form" method="post" enctype=multipart/form-data  data-parsley-validate class="form-horizontal form-label-left" action="{{route('publisher.settings.update',$id)}}">
           {{method_field('PUT')}}
            @csrf

            <input type="hidden" name="campaignId" value="{{ $account->id }}">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="merchant">Merchant Id <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" id="merchant" value="{{ $account->merchant}}"  name="merchant"  class="form-control">
                @if ($errors->has('merchant')) 
                <div class="error-custom"> {{$errors->first('merchant') }} </div>
                @endif
              </div>
            </div>



           
            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <button class="btn btn-primary" type="button"><a href="{{route('publisher.settings.index')}}" class="text-white">Cancel</a></button>
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" id="submit" class="btn btn-success">Submit</button>
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>   
</div>
@endsection
