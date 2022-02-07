@extends('layouts.publisher')
@section('content')

<!-- top tiles -->
<div class="row dashboarddiv" style="display: inline-block;" >
  <div class="tile_count">
    
    @if(Session::get('message'))
<div class="alert alert-success alert-dismissible " role="alert">
  <button type="button" class="close btn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
  </button>
  <strong>Success!</strong> {{ Session::get('message') }}
</div>
@endif  
    
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>User Profile <a class="edit-profile"><i class="fa fa-edit"></i> </a></h3>

        </div>

      </div>

      <div class="clearfix"></div>

      <div class="row">
        <form method="post" enctype=multipart/form-data action="{{route('publisher.profile.store')}}" style="width: 100%">
          @csrf
        
          <div class="col-md-7 col-sm-7"> 

            <div class="item form-group">
              <label class="col-form-label col-md-2 col-sm-2 label-align" > First Name<span class="required">*</span> </label>
              <div class="col-md-10 col-sm-10 ">
                <input type="text" name="first_name" value="{{ $publisher->first_name }}" class="form-control readonly" placeholder="First Name" readonly/>
                @if ($errors->has('first_name')) 
                <div class="error-custom"> {{$errors->first('first_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-2 col-sm-2 label-align" >Last Name <span class="required">*</span> </label>
              <div class="col-md-10 col-sm-10 ">
                <input type="text" name="last_name" value="{{ $publisher->last_name }}" class="form-control readonly" placeholder="Last Name" readonly/>
                @if ($errors->has('last_name')) 
                <div class="error-custom"> {{$errors->first('last_name') }} </div>
                @endif
              </div>
            </div>

      
            <div class="item form-group">
              <label class="col-form-label col-md-2 col-sm-2 label-align" > Email <span class="required">*</span> </label>
              <div class="col-md-10 col-sm-10 ">
                <input type="text" name="email" value="{{ $publisher->email }}" class="form-control" placeholder="email" readonly />
                @if ($errors->has('email')) 
                <div class="error-custom"> {{$errors->first('email') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-2 col-sm-2 label-align"> Telegram ID </label>
              <div class="col-md-10 col-sm-10">
                <input type="text" name="telegram_link" value="{{ @$publisher->telegram_link }}" class="form-control readonly" placeholder="Telegram ID" readonly/>
                @if ($errors->has('telegram_link')) 
                <div class="error-custom"> {{$errors->first('telegram_link') }} </div>
                @endif
              </div>
            </div>

            
            <div class="item form-group showdiv">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <a href="/advertiser/profile" ><button class="btn btn-primary" type="button"> Cancel </button></a>
                <button type="submit" id="submit" class="btn btn-success">Update</button>
              </div>
            </div>

          </div>

        </form>

      </div>
    </div>

  </div>
</div>
@endsection