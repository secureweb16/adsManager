@extends('layouts.publisher')
@section('content')
@php
$time = array('00:00 AM','01:00 AM','02:00 AM','03:00 AM','04:00 AM','05:00 AM','06:00 AM','07:00 AM','08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 PM',
'01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM','07:00 PM','08:00 PM','09:00 PM','10:00 PM','11:00 PM');
@endphp

<div class="pubcreattelgrmads">
<div class="page-title">
<div class="title_left">
<h3>Create Account</h3>
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
@if(Session::get('error'))
<div class="alert alert-error alert-dismissible " role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
</button>
<strong>Error!</strong> {{ Session::get('error') }}
</div>
@endif
<form  method="POST" action="{{route('publisher.settings.telegram.updatefreq')}}">         
@csrf
<div class="item form-group">
<label class="col-form-label col-md-3 col-sm-3 label-align">Telegram Group<span class="required">*</span> </label>
<div class="col-md-9 col-sm-9 ">
  <div class="inner_filedsec">
   <input type="text" name="telegram_group" class="form-control" placeholder="Telegram Group" value="{{ $group->telegram_group }}"  readonly />
   @if ($errors->has('telegram_group')) 
   <div class="error-custom"> {{$errors->first('telegram_group') }} </div>
   @endif
 </div>
</div>
</div>
<input type="hidden" name="group_id" value="{{ $group->id }}">
<div class="item form-group">
<label class="col-form-label col-md-3 col-sm-3 label-align">Frequency Of Ad <span class="required">*</span> </label>                
<div class="col-md-9 col-sm-9 ">
<div class="inner_filedsec">
  <input type="number" name="frequency_of_ads" class="form-control" placeholder="Frequency of ads" value="{{ $group->frequency_of_ads }}" />
  <!-- <span>If you set a frequency of 4 then we only show 1 add every 4 hours </span> -->
  @if ($errors->has('frequency_of_ads')) 
  <div class="error-custom"> {{$errors->first('frequency_of_ads') }} </div>
  @endif
</div>
</div>
</div>


<div class="item form-group">
<label class="col-form-label col-md-3 col-sm-3 label-align"> Frequency Type <span class="required">*</span> </label>
<div class="col-md-9 col-sm-9 ">
<div class="inner_filedsec d-flex">
  <div class="radiobtn spaceR">
    <label for="custom_days">
      <input type="radio" name="frequency_type" value="minutes" @if($group->frequency_type == 'minutes') checked @endif>
      <span class="checkmark"></span>Minutes
    </label><br>
  </div>
  <div class="radiobtn">
    <label for="all_days">
      <input type="radio" name="frequency_type" value="hours" @if($group->frequency_type == 'hours') checked @endif>
      <span class="checkmark"></span> Hours
    </label><br>
  </div>                  
</div>
</div>
</div>

<div class="item form-group">
<label class="col-form-label col-md-3 col-sm-3 label-align" for="days"> Hours <span class="required">*</span> </label>
<div class="col-md-9 col-sm-9">
<div class="d-flex">
	<div class="radiobtn spaceR">
   <label for="all_days"><input type="radio" name="hours_type" value="All Days" id="all_days" @if($group->hours_type == 'All Days') checked @endif> 
     <span class="checkmark"></span> All Days</label>
   </div>
   <div class="radiobtn">
     <label for="custom_days"><input type="radio" name="hours_type" value="Custom Days" @if($group->hours_type == 'Custom Days') checked @endif> 
      <span class="checkmark"></span> Custom Days</label>
    </div>
  </div>
  @if ($errors->has('hours_type'))
  <div class="error-custom"> {{$errors->first('hours_type') }} </div>
  @endif             
  @php
  $from_all_days = date('h:i A',strtotime($group->from_all_days));
  $to_all_days = date('h:i A',strtotime($group->to_all_days));
  @endphp

  @php 
  if($group->hours_type == 'All Days') { $alldays = $daysArray = []; } 
  @endphp
  
  <div class="all-days" @if($group->hours_type == 'Custom Days') style="display:none;" @endif>
    <div class="cmnselectdays row">                 
      <div class="cmnselectdaystime col-md-9">
        <select name="from_all_days" class="form-control" onchange="getOptionStart(this)">
          @foreach($time as $key => $t)
          <option value="{{$t}}" @if($t == $from_all_days) selected @endif key="{{$key}}">{{$t}}</option>
          @endforeach
        </select>
        <span>-</span>
        <select name="to_all_days" class="form-control" onchange="getOptionEnd(this)">
          @foreach($time as $key => $t)
          <option value="{{$t}}" @if($t == $to_all_days) selected @endif key="{{$key}}">{{$t}}</option>
          @endforeach
        </select>
      </div> 
    </div>
  </div>
  @if ($errors->has('from_time'))
  <div class="error-custom"> Please select atleast one day </div>
  @endif 
  <div class="select-days" @if($group->hours_type == 'All Days') style="display:none;" @endif>
    <div class="cmnselectdays row">               
      <div class="selectdaynamecol col-md-3">
        <label for="Monday"><input type="checkbox" id="Monday" name="days[]" value="Monday" @if(in_array('Monday',$alldays)) checked @endif>
          <span class="checkmark"></span> Monday </label>  
        </div>
        <div class="cmnselectdaystime col-md-9">
          <select class="Monday start" name="from_time[]" class="form-control" @if(!in_array('Monday',$alldays)) disabled @endif onchange="getCustomOptionStart('Monday')">
            @foreach($time as $key => $t)
            <option value="{{$t}}" @if(isset($daysArray['Monday']) && ($daysArray['Monday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
            @endforeach
          </select>
          <span>-</span>
          <select class="Monday end" name="to_time[]" class="form-control" @if(!in_array('Monday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Monday')">
            @foreach($time as $key => $t)
            <option value="{{$t}}" @if(isset($daysArray['Monday']) && ($daysArray['Monday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
            @endforeach
          </select>
        </div> 
      </div>
      <div class="cmnselectdays row">
        <div class="selectdaynamecol col-md-3">                           
          <label for="Tuesday"><input type="checkbox" id="Tuesday" name="days[]" value="Tuesday" @if(in_array('Tuesday',$alldays)) checked @endif>
            <span class="checkmark"></span> Tuesday </label>
          </div>
          <div class="cmnselectdaystime col-md-9">
            <select class="Tuesday start" name="from_time[]" class="form-control" @if(!in_array('Tuesday',$alldays)) disabled @endif onchange="getCustomOptionStart('Tuesday')">
              @foreach($time as $key => $t)
              <option value="{{$t}}" @if(isset($daysArray['Tuesday']) && ($daysArray['Tuesday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
              @endforeach
            </select>
            <span>-</span>
            <select class="Tuesday end" name="to_time[]" class="form-control" @if(!in_array('Tuesday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Tuesday')">
              @foreach($time as $key => $t)
              <option value="{{$t}}" @if(isset($daysArray['Tuesday']) && ($daysArray['Tuesday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
              @endforeach
            </select> 
          </div>
        </div>
        <div class="cmnselectdays row">
          <div class="selectdaynamecol col-md-3"> 
            <label for="Wednesday"><input type="checkbox" id="Wednesday" name="days[]" value="Wednesday" @if(in_array('Wednesday',$alldays)) checked @endif>
              <span class="checkmark"></span> Wednesday </label>
            </div>
            <div class="cmnselectdaystime col-md-9">
              <select class="Wednesday start" name="from_time[]" class="form-control" @if(!in_array('Wednesday',$alldays)) disabled @endif onchange="getCustomOptionStart('Wednesday')">
                @foreach($time as $key => $t)
                <option value="{{$t}}" @if(isset($daysArray['Wednesday']) && ($daysArray['Wednesday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                @endforeach
              </select>
              <span>-</span>
              <select class="Wednesday end" name="to_time[]" class="form-control" @if(!in_array('Wednesday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Wednesday')">
                @foreach($time as $key => $t)
                <option value="{{$t}}" @if(isset($daysArray['Wednesday']) && ($daysArray['Wednesday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="cmnselectdays row">
            <div class="selectdaynamecol col-md-3"> 
              <label for="Thursday"><input type="checkbox" id="Thursday" name="days[]" value="Thursday" @if(in_array('Thursday',$alldays)) checked @endif>
                <span class="checkmark"></span> Thursday </label>
              </div>
              <div class="cmnselectdaystime col-md-9">
                <select class="Thursday start" name="from_time[]" class="form-control" @if(!in_array('Thursday',$alldays)) disabled @endif onchange="getCustomOptionStart('Thursday')">
                  @foreach($time as $key => $t)
                  <option value="{{$t}}" @if(isset($daysArray['Thursday']) && ($daysArray['Thursday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                  @endforeach
                </select>
                <span>-</span>
                <select class="Thursday end" name="to_time[]" class="form-control" @if(!in_array('Thursday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Thursday')">
                  @foreach($time as $key => $t)
                  <option value="{{$t}}" @if(isset($daysArray['Thursday']) && ($daysArray['Thursday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="cmnselectdays row">
              <div class="selectdaynamecol col-md-3">                             
                <label for="Friday"><input type="checkbox" id="Friday" name="days[]" value="Friday" @if(in_array('Friday',$alldays)) checked @endif>
                  <span class="checkmark"></span> Friday </label>
                </div>
                <div class="cmnselectdaystime col-md-9">
                  <select class="Friday start" name="from_time[]" class="form-control" @if(!in_array('Friday',$alldays)) disabled @endif onchange="getCustomOptionStart('Friday')">
                    @foreach($time as $key => $t)
                    <option value="{{$t}}" @if(isset($daysArray['Friday']) && ($daysArray['Friday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                    @endforeach
                  </select>
                  <span>-</span>
                  <select class="Friday end" name="to_time[]" class="form-control" @if(!in_array('Friday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Friday')">
                    @foreach($time as $key => $t)
                    <option value="{{$t}}" @if(isset($daysArray['Friday']) && ($daysArray['Friday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="cmnselectdays row">
                <div class="selectdaynamecol col-md-3"> 
                  <label for="Saturday"><input type="checkbox" id="Saturday" name="days[]" value="Saturday" @if(in_array('Saturday',$alldays)) checked @endif>
                    <span class="checkmark"></span> Saturday </label>
                  </div>
                  <div class="cmnselectdaystime col-md-9">
                    <select class="Saturday start" name="from_time[]" class="form-control" @if(!in_array('Saturday',$alldays)) disabled @endif onchange="getCustomOptionStart('Saturday')">
                      @foreach($time as $key => $t)
                      <option value="{{$t}}" @if(isset($daysArray['Saturday']) && ($daysArray['Saturday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                      @endforeach
                    </select>
                    <span>-</span>
                    <select class="Saturday end" name="to_time[]" class="form-control" @if(!in_array('Saturday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Saturday')">
                      @foreach($time as $key => $t)
                      <option value="{{$t}}" @if(isset($daysArray['Saturday']) && ($daysArray['Saturday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="cmnselectdays row">
                  <div class="selectdaynamecol col-md-3"> 
                    <label for="Sunday"><input type="checkbox" id="Sunday" name="days[]" value="Sunday" @if(in_array('Sunday',$alldays)) checked @endif>
                      <span class="checkmark"></span> Sunday </label>
                    </div>
                    <div class="cmnselectdaystime col-md-9">
                      <select class="Sunday start" name="from_time[]" class="form-control" @if(!in_array('Sunday',$alldays)) disabled @endif onchange="getCustomOptionStart('Sunday')">
                        @foreach($time as $key => $t)
                        <option value="{{$t}}" @if(isset($daysArray['Sunday']) && ($daysArray['Sunday']['from'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                        @endforeach
                      </select>
                      <span>-</span>
                      <select class="Sunday end" name="to_time[]" class="form-control" @if(!in_array('Sunday',$alldays)) disabled @endif onchange="getCustomOptionEnd('Sunday')">
                        @foreach($time as $key => $t)
                        <option value="{{$t}}" @if(isset($daysArray['Sunday']) && ($daysArray['Sunday']['to'] == $t)) selected @endif key="{{$key}}">{{$t}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                @if ($errors->has('days'))
                <div class="error-custom"> {{$errors->first('days') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <input type="submit" name="save" class="btn btn-success" value="Submit">
                <button type="button" class="btn btn-warning" ><a href="{{route('publisher.settings.telegram.group')}}" class="text-white">Cancel</a></button>
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>   
</div>

<script>

  $("input[name='hours_type']").click(function(){
    var value = $(this).val();      
    if(value == 'Custom Days'){
      $('.select-days').show();
      $('.all-days').hide();
    }else{
      $('.select-days').hide();       
      $('.all-days').show();        
    }
  });
</script>
@endsection