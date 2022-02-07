@extends('layouts.advertiser')
@section('content')
<div class="rgtouterwrap">
  <div class="page-title">
    <div class="title_left">
      <h3>Trash campaign <small>List</small></h3>
    </div>
     <div class="title_right">
      <div class="col-md-4 col-sm-4 form-group pull-right ">
        <div class="input-group">
          <a href="{{URL::to('/advertiser/campaigns')}}">
            <button type="button" class="btn btn-primary btn-sm">All Campaign</button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="row clearfix" style="display: block;">
    <div class="col-md-12 col-sm-6">
    @if(Session::get('message'))
      <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ Session::get('message') }}
      </div>
      @endif
      @if(Session::get('error'))
      <div class="alert alert-danger alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Success!</strong> {{ Session::get('error') }}
      </div>
      @endif  
      <div class="x_panel">
        <div class="x_content tblcontent">
          <table class="table" id="datatable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Campaign Name</th>
                <th>Remaing Budget</th>
                <th>Landing Url</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $i=1; @endphp
              @forelse($campaigns as $campaign)
              <tr>
                <th scope="row">{{ $i }}</th>
                <td>{{ $campaign->campaign_name }}</td>
                <td>{{ $campaign->remaing_total+$campaign->remaing_daily }} </td>
                <td style="">{{ $campaign->landing_url }}</td>
                <td>
                  <button type="button" class="btn btn-primary btn-sm">
                    <a href="{{route('advertiser.campaigns.restore',encrypt($campaign->id))}}" class="text-white">Restore</a>
                  </button>
                  <button type="submit" class="btn btn-danger btn-sm">
                    <a href="{{route('advertiser.campaigns.deletepermanent',encrypt($campaign->id))}}" class="text-white">Delete Permanent</a>
                  </button>
                </td>
              </tr>
              @php $i++ @endphp
              @empty
              <tr>
                <td>No Campaigns exit!</td>
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