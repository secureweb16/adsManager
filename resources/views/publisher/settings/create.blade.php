@extends('layouts.publisher')
@section('content')

<div class="">
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

            @php 
              if(Session::get('address')){
                $walletaddress = Session::get('address');
              }else{
                $account =   publisher_mearchant_id(Auth::user()->id);
                $walletaddress = (isset($account->wallet_address))?$account->wallet_address:'';
                $verify = (isset($account->verify))?$account->verify:'';
                $locked = (isset($account->locked))?$account->locked:'';
                if(isset($account->updated_at)){
                  $start = strtotime($account->updated_at); 
                  $end = strtotime(date('Y-m-d'));
                  $days_between = ceil(abs($end - $start) / 86400);
                  $remdays = 30-$days_between;
                }else{
                  $days_between = 30;
                }
                $remdays = 30-$days_between;
              }
            @endphp

           <!--  @if(Session::get('errormsg'))
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <strong>Error!</strong> {{ Session::get('errormsg') }}
            </div>
            @endif -->
<!-- 
            @if(Session::get('message'))
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <strong>Success!</strong> {{ Session::get('message') }}
            </div>
            @endif -->

            <!-- @if($verify == '0' && !Session::get('message'))
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              Your account is not verified please click on verify.
            </div>
            @endif -->

          <!--   @if($verify == '1' && $locked == '0')
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              Please clicked on locked.
            </div>
            @endif -->

            <!-- <div id="custom_msg" class="alert  alert-dismissible" role="alert"> </div>
            @if($remdays > 0)
            <div class="item form-group">              
              <label class="col-form-label col-md-3 col-sm-3 label-align"> </label> 
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <p>you can change the wallet address after {{$remdays}} </p>
                </div>
              </div>             
            </div>
            @endif -->
        <div class="x_content">
          <form data-parsley-validate action="{{route('publisher.settings.store')}}" method="POST">         
            @csrf
           
            <div class="item form-group">              
              <label class="col-form-label col-md-3 col-sm-3 label-align"> </label>   
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <p>Please Add Your BEP20 BUSD wallet address </p>
                </div>
              </div>            
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="wallet"> Wallet Address <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <input type="text" name="wallet_address" class="form-control" placeholder="Enter Wallet Address" value="{{ $walletaddress }}" />
                  @if ($errors->has('wallet_address')) 
                  <div class="error-custom"> {{$errors->first('wallet_address') }} </div>
                  @endif
                </div>
              </div>
            </div>

           <!--  <div class="item form-group" id="resign" style="display:none;">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="wallet"> Wallet Address Change Resign <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <input type="text" name="resign" class="form-control" placeholder="Wallet Address Change Resign" />
              </div>
            </div> -->

            <div class="item form-group">
              <div class="col-md-2 col-sm-2 offset-md-3">
                <div class="d-flex">
                    <input type="submit" name="save" class="btn btn-success" value="Submit" @if($verify == '1') disabled @endif>
                    <!-- <input type="button" name="verify" class="btn btn-success verify" value="Verify" @if($verify == '1') disabled @endif> -->
                    <!-- <input type="button" name="locked" class="btn btn-success locked" value="Locked" @if($verify == '0' || $locked == '1') disabled @endif> -->
               <!--    @if($remdays <= 0 && !empty($walletaddress))              
                    <input type="button" name="locked" class="btn btn-success change_wallet" value="Change Wallet Address">
                  @endif -->
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
</div>

<script type="text/javascript">

  $('.change_wallet').click(function(){
    $('#resign').show();
    $('input[name="resign"]').attr('required','required');
    $('input[name="save"]').removeAttr('disabled');
  })

  $('.verify').click(function(){
    $.ajax({
      type:'GET',
      url:'/publisher/settings/payment-update',
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      data:{},      
      success:function(res) {
        console.log('res',res);
        $('#custom_msg').html(res);
        if(res != 'That is not a valid address for that coin!'){
          $('input[name="save"]').attr('disabled','disabled');
          $('input[name="verify"]').attr('disabled','disabled');
          $('input[name="locked"]').removeAttr('disabled');
        }else{
          $('#custom_msg').css('color','red');
        }
      }
    });
  });

  $('.locked').click(function(){
    $.ajax({
      type:'GET',
      url:'/publisher/settings/payment-locked',
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      data:{},      
      success:function(res) {
        $('input[name="locked"]').attr('disabled','disabled');
      }
    });
  });

</script>

@endsection


























