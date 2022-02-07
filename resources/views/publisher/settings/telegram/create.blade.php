@extends('layouts.publisher')
@section('content')

@php
$time = array('00:00 AM','01:00 AM','02:00 AM','03:00 AM','04:00 AM','05:00 AM','06:00 AM','07:00 AM','08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 AM',
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
		<br/>
		@if(Session::get('error'))
		<div class="alert alert-error alert-dismissible " role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong>Error!</strong> {{ Session::get('error') }}
		</div>
		@endif
		<div class="error-div" style="color: red; margin-left: 15%; padding-bottom: 1%; font-size: 15px;"></div>
		<form  method="POST" action="{{route('publisher.settings.telegram.group')}}">         
			@csrf
			<div class="item form-group">
				<label class="col-form-label col-md-3 col-sm-3 label-align">Telegram Group<span class="required">*</span> </label>
				<div class="col-md-9 col-sm-9 testttt">
					<div class="inner_filedsec">
						<input type="text" name="telegram_group" class="form-control" placeholder="Telegram group or channel" value="{{ old('telegram_group') }}" />
						@if ($errors->has('telegram_group')) 
						<div class="error-custom"> {{$errors->first('telegram_group') }} </div>
						@endif
					</div>
				</div>
			</div>

			<div class="item form-group">
				<label class="col-form-label col-md-3 col-sm-3 label-align">Frequency Of Ad <span class="required">*</span> </label>
				<div class="col-md-9 col-sm-9 ">
					<div class="inner_filedsec">
						<input type="number" name="frequency_of_ads" class="form-control" placeholder="Frequency of ad" value="{{ old('frequency_of_ads') }}" />
						<!-- <span class="ifsetuptext">If you set a frequency of 4 then we only show 1 add every 4 hours </span> --> 
						<h4 class="bot-setup"><a href="/publisher/bot-setup"> Bot setup </a></h4>
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
								<input type="radio" name="frequency_type" value="minutes">
								<span class="checkmark"></span>Minutes
							</label>
						</div>
						<div class="radiobtn">
							<label for="all_days">
								<input type="radio" name="frequency_type" value="hours" id="all_days">
								<span class="checkmark"></span> Hours
							</label>
						</div>									
					</div>
				</div>
			</div>

			<div class="item form-group">
				<label class="col-form-label col-md-3 col-sm-3 label-align" for="days"> Hours <span class="required">*</span> </label>
				<div class="col-md-9 col-sm-9 ">
					<div class="inner_filedsec">
						<div class="d-flex">
							<div class="radiobtn spaceR">
								<label for="all_days">
									<input type="radio" name="hours_type" value="All Days" id="all_days" >
									<span class="checkmark"></span> All Days
								</label>
							</div>
							<div class="radiobtn">
								<label for="custom_days">
									<input type="radio" name="hours_type" value="Custom Days" id="custom_days">
									<span class="checkmark"></span>Custom Days
								</label>
							</div>
						</div>
						@if ($errors->has('hours_type'))
						<div class="error-custom"> {{$errors->first('hours_type') }} </div>
						@endif
						<div class="all-days" style="display:none;">
							<div class="cmnselectdays row">									
								<div class="cmnselectdaystime col-md-9">
									<select name="from_all_days" class="form-control" onchange="getOptionStart(this)">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select name="to_all_days" class="form-control" onchange="getOptionEnd(this)">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select>
								</div> 
							</div>
						</div>
						@if ($errors->has('from_time'))
						<div class="error-custom"> Please select atleast one day </div>
						@endif 
						<div class="select-days" style="display:none;">
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">
									<label for="Monday">
										<input type="checkbox" id="Monday" name="days[]" value="Monday">
										<span class="checkmark"></span> Monday 
									</label>  
								</div>
								<div class="cmnselectdaystime col-md-9">
									<select class="Monday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Monday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select class="Monday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Monday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select>
								</div> 
							</div>
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">														
									<label for="Tuesday">
										<input type="checkbox" id="Tuesday" name="days[]" value="Tuesday">
										<span class="checkmark" key="{{$key}}"></span> Tuesday 
									</label>
								</div>
								<div class="cmnselectdaystime col-md-9">
									<select class="Tuesday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Tuesday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select class="Tuesday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Tuesday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select> 
								</div>
							</div>
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">	
									<label for="Wednesday"><input type="checkbox" id="Wednesday" name="days[]" value="Wednesday"><span class="checkmark"></span> Wednesday </label>
								</div>
								<div class="cmnselectdaystime col-md-9">
									<select class="Wednesday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Wednesday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select class="Wednesday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Wednesday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">	
									<label for="Thursday"><input type="checkbox" id="Thursday" name="days[]" value="Thursday"><span class="checkmark"></span> Thursday </label>
								</div>
								<div class="cmnselectdaystime col-md-9">
									<select class="Thursday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Thursday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select class="Thursday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Thursday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">								
									<label for="Friday"><input type="checkbox" id="Friday" name="days[]" value="Friday"><span class="checkmark"></span> Friday </label>
								</div>
								<div class="cmnselectdaystime col-md-9">
									<select class="Friday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Friday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
										@endforeach
									</select>
									<span>-</span>
									<select class="Friday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Friday')">
										@foreach($time as $key => $t)
										<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="cmnselectdays row">
								<div class="selectdaynamecol col-md-3">	
									<label for="Saturday">
										<input type="checkbox" id="Saturday" name="days[]" value="Saturday"><span class="checkmark"></span> Saturday </label>
									</div>
									<div class="cmnselectdaystime col-md-9">
										<select class="Saturday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Saturday')">
											@foreach($time as $key => $t)
											<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
											@endforeach
										</select>
										<span>-</span>
										<select class="Saturday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Saturday')">
											@foreach($time as $key => $t)
											<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="cmnselectdays row">
									<div class="selectdaynamecol col-md-3">	
										<label for="Sunday">
											<input type="checkbox" id="Sunday" name="days[]" value="Sunday"><span class="checkmark"></span> Sunday </label>
										</div>
										<div class="cmnselectdaystime col-md-9">
											<select class="Sunday start" name="from_time[]" class="form-control" disabled onchange="getCustomOptionStart('Sunday')">
												@foreach($time as $key => $t)
												<option value="{{$t}}" key="{{$key}}">{{$t}}</option>
												@endforeach
											</select>
											<span>-</span>
											<select class="Sunday end" name="to_time[]" class="form-control" disabled onchange="getCustomOptionEnd('Sunday')">
												@foreach($time as $key => $t)
												<option value="{{$t}}" key="{{$key}}" @if($t == '01:00 AM') selected @endif>{{$t}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
						</div><!--col-md-9-->
					</div>

					<div class="dashboarddiv" style="display: none;" >
						<div class="modal_body">
							<h2 class="text-center">Bot setup</h2>
							<div class="tile_count">
								<div class="bootsetupsteps">
									<div class="row">
										<div class="col-md-6">
											<div class="video_section">
												<video width="100%" height="auto" controls>
													<source src="{{ asset('botcontent/how-to-login.mp4') }}" type="video/mp4">
													</video>
												</div>
											</div>
											<div class="col-md-6">
												<p> 1. Copy the <b>@MoonLaunch_TGBot</b> bot. </p>
												<div class="row">
													<div class="col-md-6">
														<p> 2. Go to your telegrm acount and add the bot in your group <strong>as an administrator</strong>. For this step, please review the screen-shot given below.</p>
														<div class="stepsimg">
															<img src="{{ asset('botcontent/groupimg.png') }}">
														</div>
													</div>
													<div class="col-md-6">
														<p> 3. Make sure the group is publicly. For this step, please review the screen-shot given below.</p>
														<div class="stepsimg">
															<img src="{{ asset('botcontent/publicimg.png') }}">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-12" style="margin-top: 30px;">															
												<div class="row">
													<div class="col-md-6">
														<p> 4. Click on verify button the group dasboard for verify the bot is install in your group or not.</p>
														<div class="stepsimg">
															<img src="{{ asset('botcontent/dashboard.png') }}">
														</div>
													</div>
													<div class="col-md-6">
														<p> 5. If You received test message in your group then it is successfully installed. </p>
														<div class="stepsimg">
															<img src="{{ asset('botcontent/testmsg.png') }}">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center spaceT40">
												<input type="submit" name="save" class="btn btn-success" value="Confirm">  
											</div>
										</div>
									</div>      
								</div>
							</div>
						</div>

						<div class="item form-group">
							<div class="col-md-6 col-sm-6 offset-md-3">									
								<button type="button" id="savebtn" name="savebtn" class="btn btn-succes"> Submit </button>
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

	$('input[name="telegram_group"]').focusout(function(){
    let gruopname = $(this).val();
    let name = gruopname.split("/");
    if(name.length > 1){ $('.error-div').html('just use what’s after the link'); } else{ $('.error-div').html(''); }
  });


	$('#savebtn').click(function(){
		$('.dashboarddiv').show();
	});

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