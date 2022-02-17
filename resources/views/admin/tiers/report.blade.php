@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap advrtsr_admlist">
  <div class="page-title">
    <div class="title_left">
      <h3>Tier <small>Report</small></h3>
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
                <th>Publisher</th>
                <th>Total Publish</th>
                <th>Total Clicks</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($alltier as $tier)
              <tr>
                <th scope="row"> {{ $i }} </th>
                <td> {{ $tier->tier_name }} </td>
                <td> @php 
                  $publisher = str_replace("[","",$tier->publisher);
                  $publisher = str_replace("]","",$publisher);
                  $publisher = explode(",",$publisher);                  
                @endphp  
                @foreach($publisher as $pub)
                  {{ UserEmail($pub) }} <br/>
                @endforeach
                 </td>
                <td> {{ $tier->minimun_cpc }} </td>
                <td> {{ $tier->payout }} </td>
                <td>
                  <button type="button" class="btn btn-warning btn-sm">
                    <a href="{{ route('admin.tiers.edit',encrypt($tier->id)) }}" class="text-white">Edit</a>
                  </button>
                  <form action="{{ route('admin.tiers.destroy',encrypt($tier->id)) }}" method="post">
                    @csrf {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger">Delete</button>
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