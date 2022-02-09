@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap advrtsr_admlist">
  <div class="page-title">
    <div class="title_left">
      <h3>Tier <small>List</small></h3>
    </div>
    <div class="title_right">
      <div class="col-md-4 col-sm-4 form-group pull-right ">
        <div class="input-group">
          <a href="{{route('admin.tiers.index')}}">
            <button type="button" class="btn btn-primary btn-sm">All Tiers</button>
          </a>
        </div>
      </div>
    </div>
  </div>  
  
  <div class="clearfix"></div>
  <div class="row clearfix" style="display: block;">
    <div class="col-md-12 col-sm-6">      
      @if(Session::get('success'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Error!</strong> {{ Session::get('success') }}
      </div>
      @endif

      <div class="x_panel">
        <div class="x_content tblcontent">
          <table class="table" id="datatable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tier Name</th>
                <th>Minimum CPC</th>
                <th>Payout</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($alltier as $tier)
              <tr>
                <th scope="row"> {{ $i }} </th>
                <td> {{ $tier->tier_name }} </td>                
                <td> {{ $tier->minimun_cpc }} </td>
                <td> {{ $tier->payout }} </td>
                <td>
                  <button type="button" class="btn btn-warning btn-sm">
                    <a href="{{ route('admin.tiers.restore',encrypt($tier->id)) }}" class="text-white">Restore</a>
                  </button>
                  <form action="{{ route('admin.tiers.trash.delete',encrypt($tier->id)) }}" method="post">
                    @csrf {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger">Delete Permanent</button>
                  </form>
                </td>
              </tr>
              @php $i++ @endphp
              @empty
              <tr>
                <td>No Tier exist!</td>
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