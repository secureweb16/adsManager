@extends('layouts.admin')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Update Advertiser</h3>
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
          <form method="POST" action="{{route('admin.user.update')}}">
            @csrf
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="first_name" > First Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <div class="inner_filedsec">
                  <input type="text" id="first_name" value="{{ $advertisers->first_name }}"  name="first_name"  class="form-control">
                </div>
                @if ($errors->has('first_name')) 
                <div class="error-custom"> {{$errors->first('first_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="last_name" > Last Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <div class="inner_filedsec">
                  <input type="text" id="last_name" value="{{ $advertisers->last_name }}"  name="last_name"  class="form-control">
                </div>
                @if ($errors->has('last_name')) 
                <div class="error-custom"> {{$errors->first('last_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="email" >Email <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 ">
                <div class="inner_filedsec">
                  <input type="text" id="email" value="{{ $advertisers->email }}"  name="email"  class="form-control" readonly>
                </div>
                @if ($errors->has('email')) 
                <div class="error-custom"> {{$errors->first('email') }} </div>
                @endif
              </div>
            </div>

            <input type="hidden" id="hidden_feild" value="3"  name="user_hidden"  class="form-control">
            <input type="hidden" id="hidden_update_id" value="{{ $advertisers->id }}"  name="hidden_update_id"  class="form-control">

            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <!-- <button class="btn btn-primary" type="button">Cancel</button> -->
                <button class="btn btn-primary btn-sm" type="reset">Reset</button>
                <button type="submit" id="submit" class="btn btn-success btn-sm">Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
</div>
@endsection
