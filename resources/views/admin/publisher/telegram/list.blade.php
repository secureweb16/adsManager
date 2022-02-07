@extends('layouts.admin')
@section('content')
<div class="rgtouterwrap">
  <div class="page-title">
    <div class="title_left">
      <h3>Group <small>List</small></h3>
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="row clearfix" style="display: block;">
    <div class="col-md-12 col-sm-6">
      @if(Session::get('message'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Success!</strong> {{ Session::get('message') }}
      </div>
      @endif
      <div class="x_panel">
        <div class="x_content tblcontent">
          <div id="errormsg" style="color:red;"></div>
          <table class="table" id="datatable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Publisher Name </th>
                <th>Telegram Group</th>
                <th>Frequency Of Ad</th>
                <th>Earning</th>
                <th>Status</th>
                <th>Admin Status</th>
                <th>Verified</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($groups as $group)
              @php $id = $group->id; @endphp
              <tr>
                <th scope="row">{{ $i }}</th>
                <td>{{ UserName($group->publisher_id) }}</td>
                <td>{{ $group->telegram_group }}</td>
                <td>{{ $group->frequency_of_ads }} {{ $group->frequency_type }}</td>
                <td>{{ $group->groups_earnings_sum_payable_amount }} </td>

                <td class="admin @if($group->status == 1)active @else inactive @endif">
                  <span class="adminstatus"> @if($group->status == 1) Active  @else InActive @endif </span>
                </td>

                <td class="@if($group->admin_status == 1)Active @else InActive @endif">
                  <label class="switch status">
                    <input type="checkbox" attrtid="{{$id}}" @if($group->admin_status == 1) checked @endif>
                    <span class="slider"></span>
                  </label>
                </td>
                <td class="admin">
                  <span class="checkverify class_{{$id}} @if($group->verify == 1) verified @endif" attrtid="{{$id}}"> @if($group->verify == 1 ) Verified  @else Verify @endif </span>
                </td>
                <td>
                  <a href="{{ route('admin.publishers.delete',encrypt($id)) }}" class="btn btn-danger btn-xs">Delete</a>
                </td>
              </tr>
              @php $i++ @endphp
              @empty
              <tr>
                <td>No group exit!</td>
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