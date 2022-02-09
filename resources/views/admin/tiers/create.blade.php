@extends('layouts.admin')
@section('content')

<div class="createcompnpge">
  <div class="page-title">
    <div class="title_left">
      <h3>Create Tier</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="x_panel">
        <div class="x_title">
          <h4><small>* Fields are required:</small></h4>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @if(Session::get('success'))
          <div class="alert alert-success alert-dismissible " role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> {{ Session::get('success') }}
          </div>
          @endif  
          <br />
          <form method="post" enctype=multipart/form-data action="{{route('admin.tiers.store')}}">
            @csrf
            @php $publisherOld = (!empty(old('publisher')))?old('publisher'):array(); @endphp
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" for="tier_name"> Tier Name <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <input type="text" value="{{old('tier_name')}}"  name="tier_name"  class="form-control">
                </div>
                @if ($errors->has('tier_name')) 
                <div class="error-custom"> {{$errors->first('tier_name') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Tier Description </label>
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <textarea name="tier_description"  class="form-control">{{old('tier_description')}}</textarea>
                </div>
                @if ($errors->has('tier_description')) 
                <div class="error-custom"> {{$errors->first('tier_description') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Publisher <span class="required">*</span> </label>
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <select class="select2 form-control" multiple="multiple" name="publisher[]">
                    <option value=""> Select Publisher </option>
                    @foreach($allpublisher as $publisher)
                      <option value="{{ $publisher->id }}" @if(!empty(old()) && in_array($publisher->id,$publisherOld)) selected @endif>{{ $publisher->email }}</option>
                    @endforeach
                  </select>                  
                </div>
                @if ($errors->has('publisher')) 
                <div class="error-custom"> {{$errors->first('publisher') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Minimum CPC </label>
              <div class="col-md-9 col-sm-9 add-doller">
                <div class="inner_filedsec">
                  <input type="text" value="{{old('minimun_cpc')}}"  name="minimun_cpc"  class="form-control">
                </div>
                @if ($errors->has('minimun_cpc')) 
                <div class="error-custom"> {{$errors->first('minimun_cpc') }} </div>
                @endif
              </div>
            </div>

            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align"> Payout </label>
              <div class="col-md-9 col-sm-9">
                <div class="inner_filedsec">
                  <input type="text" value="{{old('payout')}}"  name="payout"  class="form-control">
                </div>
                @if ($errors->has('payout')) 
                <div class="error-custom"> {{$errors->first('payout') }} </div>
                @endif
              </div>
            </div>


            <div class="item form-group">
              <div class="col-md-6 col-sm-6 offset-md-3">
                <button type="submit" class="btn btn-success btn-sm">Submit</button>
                <button class="btn btn-primary btn-sm" type="reset"> <a href="{{route('admin.tiers.index')}}"> Cancle </a></button>
                <button class="btn btn-primary btn-sm" type="reset">Reset</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('.select2').select2({
    tags: false,
    tokenSeparators: [',', ' ']
  });
});
</script>
@endsection
