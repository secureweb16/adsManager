@extends('layouts.admin')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Minimum CPC Bid</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
     @if(Session::get('message'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ Session::get('message') }}
      </div>
      @endif  
      <div class="x_panel">
        <div class="x_content">
          <br/>
          <form method="POST" action="{{route('admin.settings.save.email')}}">
            @csrf
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align">Adminstrater Email</label>
                <div class="col-md-9 col-sm-9">
                  <div class="inner_filedsec">
                    <input type="text" name="adminstrater_email"  class="form-control" placeholder="Enter Adminstrater Email" value="{{get_option_value('adminstrater_email',true);}}">
                  </div>
                    @if ($errors->has('adminstrater_email')) 
                       <div class="error-custom"> {{$errors->first('adminstrater_email') }} </div>
                    @endif
                </div>
            </div>

            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3 ">                  
                  <button class="btn btn-primary btn-sm" type="reset">Reset</button>
                  <button type="submit" id="submit" class="btn btn-success btn-sm">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
</div>
@endsection
