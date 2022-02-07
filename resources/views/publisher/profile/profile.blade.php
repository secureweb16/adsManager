@extends('layouts.publisher')
@section('content')

<!-- top tiles -->
<div class="row dashboarddiv" style="display: inline-block;" >
	<div class="tile_count">
		<div class="container">
			<div class="row">
				<div class="prof">	
                   <form style="padding-left:20px;" enctype=multipart/form-data data-parsley-validate action="{{route('publisher.profile.store')}}" method="POST">
                   	   <h2>Profile</h2>
                   	   @csrf
                   	   <!-- @method('POST') -->
                   	   <!--  User Id:<input type="text" name="user_id" placeholder="Enter User id"><br><br> -->
                   	   First Name:<input type="text" name="first_name" placeholder="Enter First Name" value="{{$student->userdata->first_name?$student->userdata->first_name:''}}"><br><br>
                   	   Last Name:<input type="text" name="last_name" placeholder="Enter Last Name" value="{{$student->userdata->last_name}}"><br><br>
                   	   Email:<input type="text" name="email" readonly placeholder="Enter Email" value="{{$student->userdata->email}}"><br><br>
                   	   D.O.B:<input type="text" name="dob" placeholder="Enter Date Of Birth" value="{{$student->dob}}"><br><br>
                   	    @if ($errors->has('dob')) 
		                <div class="error-custom"> {{$errors->first('dob') }} </div>
		                @endif
                   	   Image: <img class="img-responsive avatar-view" src="{{ asset('common/images/profile/'.$student->profile_image) }}" alt="Avatar" title="Change the avatar" width="100px" height="100px"><input type="file" name="profile_image" ><br><br>
                   	   Address:<textarea name="address" placeholder="Enter Address">{{$student->address}}</textarea><br><br>
                   	    @if ($errors->has('address')) 
		                <div class="error-custom"> {{$errors->first('address') }} </div>
		                @endif
                   	   Address(optional):<textarea name="address2" placeholder="Enter Address">{{$student->address2}}</textarea><br><br>
                   	  
                   	   City:<input type="text" name="city" placeholder="Enter City" value="{{$student->city}}"><br><br>
                   	    @if ($errors->has('city')) 
		                <div class="error-custom"> {{$errors->first('city') }} </div>
		                @endif
                   	   Country:<input type="text" name="country" placeholder="Enter Country" value="{{$student->country}}"><br><br>
                   	    @if ($errors->has('country')) 
		                <div class="error-custom"> {{$errors->first('country') }} </div>
		                @endif
                   	   Zip:<input type="text" name="zip" placeholder="Enter Zip" value="{{$student->zip}}"><br><br>
                   	    @if ($errors->has('zip')) 
		                <div class="error-custom"> {{$errors->first('zip') }} </div>
		                @endif
                   	   <input type="submit" name="save" class="btn btn-success" value="Submit">
                   </form>
				</div>
			</div>
		</div>
		
		
		<!-- <div class="col-md-2 col-sm-4  tile_stats_count">
			<span class="count_top"><i class="fa fa-user"></i> </span>
			<div class="count"></div>              
		</div> -->

	</div>
</div>
@endsection