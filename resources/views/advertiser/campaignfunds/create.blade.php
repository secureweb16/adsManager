@extends('layouts.advertiser')
@section('content')

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>Create Campaign</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="x_panel">
        <div class="x_title">
          <h2><small>* Fields are reuuired:</small></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />

            @if(Session::get('message'))
              <div class="alert alert-danger alert-dismissible " role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <strong>Error!</strong> {{ Session::get('message') }}
              </div>
            @endif  
          <form method="post" enctype=multipart/form-data  data-parsley-validate class="form-horizontal form-label-left" action="{{route('advertiser.campaigns.funds.add')}}">

            @csrf

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" > Select Campaign <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <select type="text"  name="campaign_id"  class="form-control">
                  <option value="">Select Campaign</option>
                  @forelse($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" @if(old('campaign_id') == $campaign->id) selected @endif>{{ $campaign->campaign_name }}</option>                  
                  @empty
                  <option value="">NO Campaign exist!</option>
                  @endforelse
                  </select>
                @if ($errors->has('campaign_id')) 
                <div class="error-custom"> {{$errors->first('campaign_id') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" > Funds Amount <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 add-doller">
                <input type="text"  name="funds_amount"  class="form-control" value="{{ old('funds_amount') }}">                 
                @if ($errors->has('funds_amount')) 
                <div class="error-custom"> {{$errors->first('funds_amount') }} </div>
                @endif
              </div>
            </div>

            
            <div class="item form-group">
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
<script>
  CKEDITOR.replace( 'description' );
</script>
@endsection
