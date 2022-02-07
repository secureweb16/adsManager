@extends('layouts.advertiser')
@section('content')

<!-- @php 
print_r($campaigns);
@endphp -->
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
          @if ($errors->any())
          <div class="error-custom">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif 
          <form id="demo-form" method="post" enctype=multipart/form-data  data-parsley-validate class="form-horizontal form-label-left" action="{{route('advertiser.campaigns.update')}}">

            @csrf

            <input type="hidden" name="campaignId" value="{{ $campaigns->id }}">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_name" > Campaign Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" id="campaign_name" value="{{ $campaigns->campaign_name }}"  name="campaign_name"  class="form-control">
                @if ($errors->has('campaign_name')) 
                <div class="error-custom"> {{$errors->first('campaign_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_budget" >Campaign Budget <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" id="campaign_budget" value="{{ $campaigns->budget }}"  name="campaign_budget"  class="form-control">
                @if ($errors->has('campaign_budget')) 
                <div class="error-custom"> {{ $errors->first('campaign_budget') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="jewelry_type" >Spend Type <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" name="spend_type">
                  <option value="">Select the Spend type</option>
                  <option value="1" @if($campaigns->spend_type == 1) selected @endif >PPC</option>
                  <option value="2" @if($campaigns->spend_type == 2) selected @endif >Daily</option>
                </select>
                @if ($errors->has('spend_type')) 
                <div class="error-custom"> {{$errors->first('spend_type') }} </div>
                @endif
              </div>
            </div>


            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"  > Pay PPC or Daily <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <input type="number" value="{{ $campaigns->pay_charges }}"  name="pay_charges"  class="form-control">
                @if ($errors->has('spend_type')) 
                <div class="error-custom"> {{$errors->first('pay_charges') }} </div>
                @endif
              </div>
            </div>            


            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Campaign Description :</label>
              <div class="col-md-9 col-sm-9 ">
                <textarea name="description">{{ $campaigns->description }}</textarea>
                @if ($errors->has('description')) 
                <div class="error-custom"> {{$errors->first('description') }} </div>
                @endif
              </div>
            </div>


            <div class="item form-group">
              <input type="hidden" name="campaign_banner" value="{{ $campaigns->banner_image }}">
              <label class="col-form-label col-md-3 col-sm-3 label-align" >Banner Image <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6">
                <input type="file" id="banner_image" name="banner_image" class="form-control">
                @if ($errors->has('banner_image')) 
                <div class="error-custom"> {{$errors->first('banner_image') }} </div>
                @endif
              </div>
              <div class="col-md-3 col-sm-3">
                <img src="{{ asset('common/images/campaignUploads/') }}/{{ $campaigns->banner_image }}" width="100px">
              </div>
            </div>


            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" >Landing Url <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" id="landing_url" value="{{ $campaigns->landing_url }}" name="landing_url" class="form-control">
                @if ($errors->has('landing_url')) 
                <div class="error-custom"> {{$errors->first('landing_url') }} </div>
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
