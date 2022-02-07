@extends('layouts.auth')
@section('content')
<div class="wrapper-div">
  <div class="animate form">
    <section class="password-update">
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <h1>Password Update</h1>
        <ul class="parsley-errors-list filled" id="parsley-id-5"><li class="parsley-required">  {{ $errors->first('password') }}</li></ul>
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
          <input id="email" type="email" name="email" value="{{ $request->email}}" class="form-control"  required="" placeholder="Email" readonly>
        </div>

        <div>
          <input id="password" type="password" name="password" class="form-control" placeholder="Password" required="" />
        </div>

        <div>
          <input id="password_confirmation" type="password" name="password_confirmation"  class="form-control" placeholder="Password confirmation" required="" />
        </div>
        <div>
          <button class="btn btn-default submit" >Reset Password</button>
        </div>
        <div class="clearfix"></div>
      </form>
    </section>
  </div>
</div>
@endsection




