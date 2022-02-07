@extends('layouts.admin')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Add Advertiser Fuds</h3>
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
        <div class="x_title">
          <h4><small>* Fields are required:</small></h4>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form method="POST"  data-parsley-validate class="form-horizontal form-label-left" action="{{route('admin.advertisers.funds')}}">
            @csrf
            
                
                  <label class="col-form-label col-md-3 col-sm-3 label-align" > Select Advertiser <span class="required">*</span> </label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="item form-group">
                      <div class="inner_filedsec">
                         <select name="advertiser"  class="form-control">
                         	<option value=""> Select Advertiser </option>
                         	@forelse($advertisers as $advertiser)
                           <option value="{{ $advertiser->id }}"> {{ $advertiser->email }} </option>
                           @empty
                           @endforelse
                         </select>
                       </div>
                     </div>
                   @if ($errors->has('advertiser')) 
                   <div class="error-custom"> {{$errors->first('advertiser') }} </div>
                   @endif
                 </div>

                <div class="">
                    <label class="col-form-label col-md-3 col-sm-3 label-align" > Amount <span class="required">*</span> </label>
                    <div class="col-md-9 col-sm-9 add-doller">
                      <div class="inner_filedsec">
                        <input type="text" value="{{old('amount')}}"  name="amount"  class="form-control">
                      </div>
                      @if ($errors->has('amount')) 
                      <div class="error-custom"> {{$errors->first('amount') }} </div>
                      @endif
                    </div>
                </div>
            </div>
          <div class="">
            <div class="col-md-6 col-sm-6 offset-md-3">
              <!-- <button class="btn btn-primary" type="button">Cancel</button> -->
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
