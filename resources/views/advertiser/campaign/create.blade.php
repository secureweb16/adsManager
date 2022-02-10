@extends('layouts.advertiser')
@section('content')

<div class="createcompnpge">
  <div class="page-title">
    <div class="title_left">
      <h3>Create Campaign</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="x_panel">
        <div class="x_title">
          <h4><small>* Fields are required:</small></h4>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form method="post" enctype=multipart/form-data action="{{route('advertiser.campaigns.create')}}">
            @csrf           
            @php $campaign_type = (!empty(old('campaign_type')))?old('campaign_type'):array(); @endphp
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Campaign Type <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <div class="cmpgntypes">
                  <ul>
                    <li>
                      <div class="cmpgntype" id="checkclick">
                        <input type="checkbox" name="campaign_type[]" value="Telegram" @if(!empty(old()) && in_array('Telegram',$campaign_type)) checked @endif>
                        <label for="telegram"> Telegram </label>
                      </div>
                    </li>
                    <li>
                      <div class="cmpgntype disabled">
                        <input type="checkbox" name="campaign_type[]" value="Twitter" @if(!empty(old()) && in_array('Twitter',$campaign_type)) checked @endif disabled>
                        <label for="twitter"> Twitter Coming Soon </label>
                      </div>
                    </li>
                    <li>
                      <div class="cmpgntype disabled">
                        <input type="checkbox" name="campaign_type[]" value="Lock screen ads" @if(!empty(old()) && in_array('Lock screen ads',$campaign_type)) checked @endif disabled>
                        <label for="lock_screen_ads"> Lock screen ads Coming Soon </label>
                      </div>
                    </li>
                  </ul>                   
                </div>

                @if ($errors->has('campaign_type')) 
                <div class="error-custom"> {{$errors->first('campaign_type') }} </div>
                @endif
              </div>
            </div> 
            
            <div id="show-hide" @if(count($campaign_type) == 0) style="display: none;" @endif>              
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
                              <input type="radio" name="campaign_tire" value="{{$tier->id}}" data-id="{{$tier->id}}" @if($tier->id == old('campaign_tire')) checked @endif>
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
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_name"> Campaign Name <span class="required">*</span> </label>
                <div class="col-md-9 col-sm-9">
                  <input type="text" id="campaign_name" value="{{old('campaign_name')}}"  name="campaign_name"  class="form-control">
                  @if ($errors->has('campaign_name')) 
                  <div class="error-custom"> {{$errors->first('campaign_name') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="headline"> Headline <span class="required">*</span> </label>
                <div class="col-md-9 col-sm-9">
                  <input type="text" id="headline" value="{{old('headline')}}"  name="headline"  class="form-control">
                  @if ($errors->has('headline')) 
                  <div class="error-custom"> {{$errors->first('headline') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="campaign_budget"> Campaign Budget <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 add-doller">
                  <input type="text" id="campaign_budget" value="{{old('campaign_budget')}}"  name="campaign_budget"  class="form-control">
                  @if ($errors->has('campaign_budget')) 
                  <div class="error-custom"> {{$errors->first('campaign_budget') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group"> 
                <label class="col-form-label col-md-3 col-sm-3 label-align"> <span class="averagecpc orange">Average PPC Bid: $<span id="average">{{ get_option_value('average_cost_value') }}</span> </span>, CPC Bid <span class="required">*</span> </label>
                <div class="col-md-9 col-sm-9 add-doller">
                  <input type="text" value="{{old('pay_ppc')}}"  name="pay_ppc"  class="form-control">
                  <div class="cpc_bid_error" style="color: #fd3b4d;"></div>
                  @if ($errors->has('pay_ppc')) 
                  <div class="error-custom biderror"> {{$errors->first('pay_ppc') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align"> Daily Budget </label>
                <div class="col-md-9 col-sm-9 add-doller">
                  <input type="text" value="{{old('pay_daily')}}"  name="pay_daily"  class="form-control">
                  @if ($errors->has('pay_daily')) 
                  <div class="error-custom"> {{$errors->first('pay_daily') }} </div>
                  @endif
                </div>
              </div>


              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="description"> Post Content (URL Not Allowed) (100 max) </label>
                <div class="col-md-9 col-sm-9">
                  <textarea name="description">{{ old('description') }}</textarea>
                  @if ($errors->has('description')) 
                  <div class="error-custom"> {{$errors->first('description') }} </div>
                  @endif
                </div>
              </div>


              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="Banner_image">Banner Image <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9">
                  <input type="file" id="banner_image" name="banner_image" class="form-control">
                  @if ($errors->has('banner_image')) 
                  <div class="error-custom"> {{$errors->first('banner_image') }} </div>
                  @endif
                  <div class="error-custom imgerror"> </div>
                </div>
              </div>


              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="Banner_image">Landing Url <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9">
                  <input type="text" id="landing_url" value="{{old('landing_url')}}" name="landing_url" class="form-control">
                  @if ($errors->has('landing_url')) 
                  <div class="error-custom"> {{$errors->first('landing_url') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align" for="button_text"> Button text <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9">
                  <input type="text" id="button_text" value="{{old('button_text')}}" name="button_text" class="form-control">
                  @if ($errors->has('button_text')) 
                  <div class="error-custom"> {{$errors->first('button_text') }} </div>
                  @endif
                </div>
              </div>

              <div class="item form-group">
                <div class="col-md-6 col-sm-6 offset-md-3">
                  <!-- <button class="btn btn-primary btn-sm" type="button">Cancel</button> -->
                  <button type="button" class="btn btn-primary btn-sm" onClick="telegram_preview()">Preview</button>
                  <button class="btn btn-primary btn-sm" type="reset">Reset</button>
                  <button type="submit" id="submit" class="btn btn-success btn-sm">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="preview-data" style="display:none;">
          <div class="inrwrapprvwcntcmp">
            <div class="image"></div>
            <div class="compnprvwdatacont">
              <div class="title">{{old('headline')}}</div>
              <div class="description">{{ old('description') }}</div>
              <a href="http://moonlaunch.media/" target="_blank" class="lounchbtn">Ad By Moon Launch Media</a>
            </div>
            <!-- <span class="close">X</span> -->
          </div>
          <div class="link"><a href="javascript:void(0);">{{$errors->first('button_text') }}</a></div>
        </div>
      </div>
    </div>
  </div>   
</div>
<script>

  function telegram_preview(){
    let title = $('.title').html();
    let desc = $('.description').html();
    let image = $('.image').html();
    let buttontext = $('.link a').html();
    if(title == '' || desc == '' || image == ''|| buttontext == ''){ return false; }
    $('.preview-data').show();
  }

  $('input[name="headline"]').focusout(function(){
    let campignName = $('#headline').val();
    $('.title').html(campignName);
  });

  $('input[name="button_text"]').focusout(function(){    
    let buttontext = $('#button_text').val();
    $('.link a').html(buttontext);
  });


  $('.close').click(function(){
    $('.preview-data').hide();
  });

  let editor = CKEDITOR.replace( 'description' );
  
  CKEDITOR.instances['description'].on("blur", function() {    
    editorContent = editor.getData();
    $.ajax({
      type:'POST',
      url:'{{ get_option_value("web_url") }}/advertiser/remove-a',
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      data:{
        editorContent:editorContent,
      },
      success:function(res) {        
        editor.setData(res);
        $('.description').html(res);
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
          $('.image').html(this);
          $(".imgerror").html('');
          $("#submit").removeAttr('disabled');
        }              
        _URL.revokeObjectURL(objectUrl);
      };
      img.src = objectUrl;
    }
  });

  $('#checkclick').click(function(){
   $('#show-hide').toggle();
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