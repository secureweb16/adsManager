@extends('layouts.admin')
@section('content')

<div class="rgtouterwrap">
  <div class="clearfix"></div>
  <div class="row clearfix" style="display: block;">
    @php 
      echo "Comming Soon!";      
    @endphp
    @if(Session::get('message'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ Session::get('message') }}
      </div>
    @endif  
    <!-- <div class="col-md-12 col-sm-6">
      <form action="{{ route('admin.import.report.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group col-md-4 col-sm-4">
          <div class="custom-file text-left">
            <input type="file" name="csv_file" class="custom-file-input" id="csv_file">
            <label class="custom-file-label" for="customFile">Choose file</label>
          </div>
          @if ($errors->has('csv_file')) 
            <div class="error-custom"> {{$errors->first('csv_file') }} </div>
          @endif
        </div>
        <div class="col-md-4 col-sm-4">
          <input type="submit" name="import" class="btn btn-primary" value="import">
        </div>
      </form>
    </div> -->
  </div>
</div>   

@endsection