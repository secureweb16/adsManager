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

          <form method="POST" action="{{route('admin.settings.save')}}">
            @csrf
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="min CPC bid">Min CPC Bid</label>
                <div class="col-md-9 col-sm-9 add-doller">
                  <div class="inner_filedsec">
                    <input type="text" value="{{get_option_value('average_min_CPC_bid')}}"  name="min_CPC_bid"  class="form-control" placeholder="Enter Min CPC Bid">                  
                  </div>
                    @if ($errors->has('min_CPC_bid')) 
                      <div class="error-custom"> {{$errors->first('min_CPC_bid') }} </div>
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
