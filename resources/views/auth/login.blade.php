@extends('layouts.auth')
@section('content')
<div class="cmnformpages">


<div class="formwrapper">
  @if(Session::get('message'))
<div class="alert alert-success alert-dismissible " role="alert">
  <button type="button" class="close btn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
  </button>
  <strong>Success!</strong> {{ Session::get('message') }}
</div>
@endif  

@if(Session::get('error'))
<div class="alert alert-danger alert-dismissible " role="alert">
  <button type="button" class="close btn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
  </button>
  <strong>Error!</strong> {{ Session::get('error') }}
</div>
@endif  
  <div class="animate form login_form">
    <section class="login_content">
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <h1>login</h1>
        <ul class="parsley-errors-list filled" id="parsley-id-5">
          <li class="parsley-required">  {{ $errors->first('email') }}</li>
        </ul>
        <div>
          <input id="email" type="email" name="email" value="{{old('email')}}" class="form-control"  required="" placeholder="Email"/>               
        </div>

        <div>
          <input id="password" type="password" name="password" class="form-control" placeholder="Password" required="" />
        </div>

        <div class="formbuttons">
          <button class="btn btn-default submit" >Log in</button>
          <a class="reset_pass" href="{{ route('password.request') }}">Lost your password?</a>
        </div>

        <div class="clearfix"></div>
        <div class="separator">
          <p class="change_link">
            <a href="/register" class="to_register btn btn-default custombtn"> Create Account </a>            
          </p>
        </div>
      </form>
    </section>
  </div>

</div>
</div>
@endsection

