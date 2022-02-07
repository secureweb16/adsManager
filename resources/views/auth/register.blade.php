@extends('layouts.auth')
@section('content')

<div class="cmnformpages">

<div class="formwrapper">
  <div class="animate form login_form">
    @if(Session::get('message'))
    <div class="alert alert-success alert-dismissible " role="alert">
      <button type="button" class="close btn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>Success!</strong> {{ Session::get('message') }}
    </div>
    @endif  
    <section class="regisret-content login_content">
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <h1>Register</h1>

         
        <div class="form-group">          
          <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="First Name"/>
          @if ($errors->has('first_name')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('first_name') }}</li>
          </ul> 
          @endif
        </div>

        <div class="form-group">          
          <input id="name" type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Last Name"/>
          @if ($errors->has('last_name')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('last_name') }}</li>
          </ul> 
          @endif    
        </div>

        <div class="form-group">          
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email"/>
          @if ($errors->has('email')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('email') }}</li>
          </ul> 
          @endif
        </div>

        <div class="form-group">          
          <input type="password" name="password" value="{{ old('password') }}" class="form-control" placeholder="Password"/>
          @if ($errors->has('password')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('password') }}</li>
          </ul> 
          @endif        
        </div>


        <div class="form-group">          
          <input type="password" name="confirm_password" value="{{ old('confirm_password') }}" class="form-control" placeholder="Confirm Password"/>
          @if ($errors->has('confirm_password')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('confirm_password') }}</li>
          </ul> 
          @endif    
        </div>

        <div class="form-group">          
            <input type="text" name="telegram_link" value="{{ old('telegram_link') }}" class="form-control" placeholder="Telegram ID"/>
            @if ($errors->has('telegram_link')) 
            <div class="error-custom"> {{$errors->first('telegram_link') }} </div>
            @endif
        </div>


        <div class="form-group checkobx">          
          <input type="radio" class="form-control" name="user_role" id="advertiser" value="3" @if(old('user_role') == 3 ) checked @endif>
          <label for="advertiser">Advertiser</label>
          <input type="radio" class="form-control" id="publisher" name="user_role" value="2" @if(old('user_role') == 2 ) checked @endif>
          <label for="publisher">Publishers</label>
          @if ($errors->has('user_role')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('user_role') }}</li>
          </ul> 
          @endif    
        </div>

        @php $plate_form = (!empty(old('plate_form')))?old('plate_form'):array(); @endphp
        <div class="form-group plate-from" style="display: none;">
          <div class="row text-left align-items-center">
            <div class="col-md-3">
               <label class="col-form-label"> Platform <span class="required">*</span> </label>
            </div>
            <div class="col-md-9">
              <input type="checkbox" name="plate_form[]" value="Telegram" @if(!empty(old()) && in_array('Telegram',$plate_form)) checked @endif>
              <label for="telegram"> Telegram </label><br>
            </div>
          </div>
       

           
          @if ($errors->has('plate_form')) 
          <ul class="parsley-errors-list filled">
            <li class="parsley-required">{{$errors->first('plate_form') }}</li>
          </ul> 
          @endif    
        </div>

        <div class="formbuttons" >
          <input type="submit" name="submit" value="Register" class="btn btn-default submit">
          <a href="/login" class="btn btn-default custombtn">Login</a>
        </div>
      </form>
    </section>
  </div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var value = $("input[type='radio']:checked").val();
    show_hide(value);
    $("input[type='radio']").click(function(){      
      show_hide($(this).val());
    });
  })
  function show_hide(value){
    if( value == '2'){
      $('.plate-from').show();
    }else{
      $('.plate-from').hide();
    }
  }
</script>
@endsection
