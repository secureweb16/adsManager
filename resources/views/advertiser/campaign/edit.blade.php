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
          <h2><small>* Fields are required:</small></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />

          <form method="post" enctype=multipart/form-data action="{{route('advertiser.campaigns.update')}}">
            @csrf
            @php $campaignType = explode(',',$campaigns->campaign_type); @endphp
            <input type="hidden" name="campaignId" value="{{ $campaigns->id }}">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Campaign Type <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <div class="cmpgntypes">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="cmpgntype">
                        <input type="checkbox" name="campaign_type[]" value="Telegram" @if(in_array('Telegram',$campaignType)) checked @endif>
                        <label for="telegram"> Telegram </label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="cmpgntype disabled">
                        <input type="checkbox" name="campaign_type[]" value="Twitter" @if(in_array('Twitter',$campaignType)) checked @endif disabled>
                        <label for="twitter"> Twitter Coming Soon </label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="cmpgntype disabled">
                        <input type="checkbox" name="campaign_type[]" value="Lock screen ads"  @if(in_array('Lock screen ads',$campaignType)) checked @endif disabled>
                        <label for="lock_screen_ads"> Lock screen ads Coming Soon </label>
                      </div>
                    </div>
                  </div>
                </div>
                @if ($errors->has('campaign_type')) 
                <div class="error-custom"> {{$errors->first('campaign_type') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Campaign Tier </label>
              <div class="col-md-9 col-sm-9">
                <div class="cmpgntypes">
                  <ul>
                    <li>
                      <div class="cmpgntire">
                        <div class="radio-fields">
                          <label for="telegram"> None
                            <input type="radio" name="campaign_tire" value="0" checked >
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                    </li>
                    @foreach($alltier as $tier)
                    <li>
                      <div class="cmpgntire">
                        <div class="radio-fields">
                          <label for="telegram"> {{ $tier->tier_name }} 
                            <input type="radio" name="campaign_tire" value="{{$tier->id}}" data-id="{{$tier->id}}" @if($tier->id == $campaigns->tire_id ) checked @endif>
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                    </li>
                    @endforeach
                  </ul>                   
                </div>
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_name" > Campaign Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <input type="text" id="campaign_name" value="{{ $campaigns->campaign_name }}"  name="campaign_name"  class="form-control">
                @if ($errors->has('campaign_name')) 
                <div class="error-custom"> {{$errors->first('campaign_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="headline"> Headline <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <input type="text" value="{{ $campaigns->headline }}"  name="headline"  class="form-control">
                @if ($errors->has('headline')) 
                <div class="error-custom"> {{$errors->first('headline') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_budget" >Campaign Budget <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 add-doller">
                <input type="text" id="campaign_budget" value="{{ $campaigns->campaign_budget }}"  name="campaign_budget"  class="form-control" readonly>
                @if ($errors->has('campaign_budget')) 
                <div class="error-custom"> {{ $errors->first('campaign_budget') }} </div>
                @endif
              </div>
            </div>

            
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> <span class="averagecpc">Average PPC Bid: $<span id="average">{{ get_option_value('average_cost_value') }}</span> </span>, CPC Bid <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9 add-doller">
                <input type="text" value="{{ $campaigns->pay_ppc }}"  name="pay_ppc"  class="form-control">
                <div class="cpc_bid_error" style="color: #fd3b4d;"></div>
                @if ($errors->has('pay_ppc')) 
                <div class="error-custom"> {{$errors->first('pay_ppc') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Daily Budget </label>
              <div class="col-md-9 col-sm-9 add-doller">
                <input type="text" value="{{ $campaigns->pay_daily }}"  name="pay_daily"  class="form-control">
                @if ($errors->has('pay_daily')) 
                <div class="error-custom"> {{$errors->first('pay_daily') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="description"> Post Content (URL Not Allowed) </label>
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
                <div class="error-custom imgerror"> </div>
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
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="button_text"> Button text <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9">
                <input type="text" value="{{ $campaigns->button_text }}" name="button_text" class="form-control">
                @if ($errors->has('button_text')) 
                <div class="error-custom"> {{$errors->first('button_text') }} </div>
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

  let editor = CKEDITOR.replace( 'description' );
  CKEDITOR.instances['description'].on("blur", function() {    
    editorContent = editor.getData();
    $.ajax({
      type:'POST',
      url:'/advertiser/remove-a',
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      data:{
        editorContent:editorContent,
      },
      success:function(res) {        
        editor.setData(res);
      }
    });
  });

  var _URL = window.URL || window.webkitURL;
  $("#banner_image").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
      img = new Image();
      var objectUrl = _URL.createObjectURL(file);

      img.onload = function () {       
        if(this.width != 600 || this.height != 300 ){
          $(".imgerror").html('Image size must be 600 X 300');
          $("#submit").attr('disabled','disabled');
        }else{
          $(".imgerror").html('');
          $("#submit").removeAttr('disabled');
        }              
        _URL.revokeObjectURL(objectUrl);
      };
      img.src = objectUrl;
    }
  });

let mincpc = "{{ get_option_value('average_min_CPC_bid') }}"; 

  $("input[name='pay_ppc']").focusout(function(){ 
    let tireid = $('.cmpgntire input[type="radio"]:checked').val();    
    if(typeof tireid != 'undefined' && tireid != 0){
      check_tire_minmum_ppc(tireid);
    }else{
      minimum_cpc_error(mincpc);
    }    
  });

  $('.cmpgntire input[type="radio"]').click(function(){    
    let tireid = $('.cmpgntire input[type="radio"]:checked').val();
    if(tireid != 0){
      check_tire_minmum_ppc($('.cmpgntire input[type="radio"]:checked').val());
     }else{
      minimum_cpc_error(mincpc);
    }    
  });

  function check_tire_minmum_ppc(tireid){    
    $.ajax({
      type:'POST',
      url:'{{ get_option_value("web_url") }}/advertiser/tire-cpc',
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      data:{
        tireid:tireid
      },
      success:function(res) {
        minimum_cpc_error(res);
      }
    });
  }

  function minimum_cpc_error(mincpc){
    mincpc = parseFloat(mincpc);    
    let ppcval = $('input[name="pay_ppc"]').val();
    ppcval = parseFloat(ppcval);
    if(ppcval >= mincpc){
      $('.cpc_bid_error').html('');          
      $('#submit').removeAttr('disabled');
    }else{
      $('.cpc_bid_error').html('The CPC must be at least '+mincpc);
      $('.biderror').hide();
      $("#submit").attr('disabled','disabled');
    }
  }
</script>
@endsection
