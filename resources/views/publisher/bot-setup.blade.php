@extends('layouts.publisher')
@section('content')
<!-- top tiles -->
<div class="dashboarddiv" style="display: inline-block;" >
	<div class="tile_count">
      <div class="bootsetupsteps">
        <div class="video_section">
          <video width="100%" height="auto" controls>
            <source src="{{ asset('botcontent/how-to-login.mp4') }}" type="video/mp4">
          </video>
        </div>
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
          <div class="col-md-6" style="margin-top: 40px">
            <p> 4. Click on verify button the group dasboard for verify the bot is install in your group or not.</p>
            <div class="stepsimg">
              <img src="{{ asset('botcontent/dashboard.png') }}">
            </div>
          </div>
          <div class="col-md-6"  style="margin-top: 40px">
            <p> 5. If You received test message in your group then it is successfully installed. </p>
            <div class="stepsimg">
              <img src="{{ asset('botcontent/testmsg.png') }}">
            </div>
          </div>
        </div>  
      </div>      
    </div>
  </div>
  </div>
</div>
@endsection