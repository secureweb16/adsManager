@extends('layouts.auth')
@section('content')
<div class="cmnformpages">

<div class="formwrapper">
  <div class="animate form">
    <section class="login_content">
      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <h1>Reset Password</h1>
        <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
        <div>
          <input id="email" type="email" name="email" value="{{old('email')}}" class="form-control"  required="" placeholder="Email"/>
        </div>

        <div>
          <button class="btn btn-default submit" >Email Password Reset Link</button>
        </div>
        <ul class="parsley-errors-list filled" id="parsley-id-5"><li class="parsley-required">  {{ $errors->first('email') }}</li></ul>
        <div class="clearfix"></div>
      </form>
    </section>
  </div>
</div>
</div>

@endsection


