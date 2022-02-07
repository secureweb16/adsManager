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
          <form method="POST" action="{{route('admin.settings.save.hours')}}">
            @csrf
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Telegram group HRS </label>
                <div class="col-md-9 col-sm-9">
                  <div class="inner_filedsec">
                    <input type="number" name="telegram_group_hrs"  class="form-control" placeholder="Telegram group HRS" value="{{get_option_value('telegram_group_hrs',true);}}">
                  </div>
                    @if ($errors->has('telegram_group_hrs')) 
                       <div class="error-custom"> {{$errors->first('telegram_group_hrs') }} </div>
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
