@extends('layouts.publisher')
@section('content')
<div class="rgtouterwrap">
  <div class="page-title">
    <div class="title_left">
      <h3>Account <small>List</small> 
       
      </h3>      
    </div>
    @if(count($accounts)>0)
    <div class="title_right">
      <div class="col-md-3 col-sm-3   form-group pull-right ">
        <div class="input-group">
          <a href="{{URL::to('/publisher/settings/create')}}">
            <button type="button" class="btn btn-success">Add New</button>
          </a>  
        </div>
      </div>
    </div> 
    @endif
  </div>

  <div class="clearfix"></div>

  <div class="row clearfix" style="display: block;">
    <div class="col-md-12 col-sm-6  ">

      @if(Session::get('message'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ Session::get('message') }}
      </div>
      @endif 
      <div class="x_panel">
        <div class="x_content tblcontent">
          <table class="table" id="datatable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Currency</th>
                <th>Wallet Address</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($accounts as $account)
              <tr>
                <th scope="row">#{{ $i }}</th>
                <td>{{ $account->currency }}</td>
                <td>{{ $account->wallet_address }}</td>
                <td>
                    @php $id = encrypt($account->id); @endphp
                    <button type="button" class="btn btn-warning"><a href="{{route('publisher.settings.edit',$id)}}" class="text-white">Edit</a></button>
                    <form action="{{route('publisher.settings.destroy',$id)}}" method="post">
                        @csrf 
                        {{method_field('DELETE')}}
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>                  
                </td>
              </tr>
              @php $i++ @endphp
              @empty
              <tr>
                <td>No account exit!</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>   

@endsection