@extends('layouts.advertiser')
@section('content')
<div class="rgtouterwrap">
  <div class="page-title">
    <div class="title_left">
      <h3> Make <small>Payment</small> <strong><a class="wht cus-back" href="{{ route('advertiser.index') }}">Back</a></strong></h3>      
    </div>
  <div class="clearfix"></div>
  <div class="row clearfix" style="display: block;">
    <div class="col-md-12 col-sm-12" id="recordID">
      <iframe width="100%" height="550px" src="{{ $paymentUrl }}"></iframe>      
    </div>
  </div>
</div>   
<script type="text/javascript">

  const myInterval = setInterval(runFunctionImageChange, 1000);

  function runFunctionImageChange() {
    var checkloade = $('#recordID iframe').contents().find('#support-coin-web').find('img').attr('src');    
    if(typeof checkloade != 'undefined'){
       clearInterval(myInterval);
      var allimg = jQuery('#recordID iframe').contents().find('#support-coin-web').find('.col-lg-6.col-sm-6.col-sm-12.mb-2').toArray();
      $(allimg).each(function (key,val) {
          if($(val).find('img').attr("alt") == 'BNB Coin (BSC Chain)'){
            $(val).find('img').attr("src","https://www.coinpayments.net/images/coins/BNB.png");
          }
          if($(val).find('img').attr("alt") == 'BNB Coin (ERC-20)'){
            $(val).find('img').attr("src","https://www.coinpayments.net/images/coins/chained/BNB.ETH.png");
          }
          if($(val).find('img').attr("alt") == 'BUSD Token (BC Chain)'){
            $(val).find('img').attr("src","https://www.coinpayments.net/images/coins/chained/BUSD.BNB.png");
          }
          if($(val).find('img').attr("alt") == 'BUSD Token (BC Chain)'){
            $(val).find('img').attr("src","https://www.coinpayments.net/images/coins/chained/BUSD.BNB.png");
          }
          if($(val).find('img').attr("alt") == 'BUSD Token (BSC Chain)'){
            $(val).find('img').attr("src","https://www.coinpayments.net/images/coins/chained/BUSD.BNBBSC.png");
          }
      });
    }
  }

</script>
@endsection