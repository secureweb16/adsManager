@extends('layouts.admin')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Create Publisher</h3>
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

          <form method="POST" action="{{route('admin.user.save')}}">
            @csrf
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="first_name" > First Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <div class="inner_filedsec">
                  <input type="text" id="first_name" value="{{old('first_name')}}"  name="first_name"  class="form-control">
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
                  <input type="text" id="last_name" value="{{old('last_name')}}"  name="last_name"  class="form-control">
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
                  <input type="text" id="email" value="{{old('email')}}"  name="email"  class="form-control">
                </div>
                @if ($errors->has('email')) 
                <div class="error-custom"> {{$errors->first('email') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="Telegram Link" >Telegram Link <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 ">
                <div class="inner_filedsec">
                  <input type="text" id="telegram_link" value="{{old('telegram_link')}}"  name="telegram_link"  class="form-control">
                </div>
                @if ($errors->has('telegram_link')) 
                <div class="error-custom"> {{$errors->first('telegram_link') }} </div>
                @endif
              </div>
            </div>
              <input type="hidden" id="hidden_feild" value="2"  name="user_hidden"  class="form-control">

            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <button class="btn btn-primary" type="button">Cancel</button>
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
