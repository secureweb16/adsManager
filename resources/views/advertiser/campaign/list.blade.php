@extends('layouts.advertiser')
@section('content')
<div class="rgtouterwrap advrtsr_admlist">
  <div class="tile_count">
    <div class="row" >                
     <div class="col-md-12">
      <form method="get" id="mydateform">          
        <div class="row input-daterange">
          <div class="col-md-12 8888">
            <div class="d-flex">
              <div class="selectdate">
                <input name="daterange" id="advreportrange" type="text" class="form-control" readonly="" value="<?php echo $feildVal; ?>">
              </div>
              <div class="filterbtn d-flex">
                <input type="submit" name="filter" id="submit" class="btn btn-primary" value="Filter">
                <button type="button" class="btn btn-warning">
                  <a href="/advertiser/campaigns" class="text-white"> Reset </a>
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <span id="errordate" style="color:red;"></span>
  </div>
</div>
<div class="page-title">
  <div class="title_left">
    <h3>Campaign <small>List</small></h3>
  </div>
  <div class="title_right">
    <div class="col-md-4 col-sm-4 form-group pull-right ">
      <div class="input-group">
        <a href="{{URL::to('/advertiser/campaigns/create')}}">
          <button type="button" class="btn btn-primary btn-sm">Add New</button>
        </a>
        <a href="{{URL::to('/advertiser/campaigns/trash')}}">
          <button type="button" class="btn btn-danger btn-sm">Trash</button>
        </a>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<div class="row clearfix" style="display: block;">
  <div class="col-md-12 col-sm-6  ">
    @if(Session::get('message'))
    <div class="alert alert-success alert-dismissible " role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <strong>Success!</strong> {{ Session::get('message') }}
    </div>
    @endif
    @if(Session::get('error'))
    <div class="alert alert-danger alert-dismissible " role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <strong>Error!</strong> {{ Session::get('error') }}
    </div>
    @endif

    <div class="x_panel">
      <div class="x_content tblcontent">
        <table class="table" id="datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Campaign Name</th>
              <th>Campaign Budget</th>
              <th>Remaing Budget</th>
              <th>CPC</th>
              <th>Clicks</th>
              <th>Remaing Daily</th>
              <th>Landing Url</th>
              <th>Admin Approval</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse($campaigns as $campaign)
            <tr>
              <th scope="row">{{ $i }}</th>
              <td>{{ $campaign->campaign_name }}</td>
              <td>{{ $campaign->campaign_budget }}</td>
              <td>{{ $campaign->remaing_total }}</td>
              <td> {{ ($campaign->get_campaign_report_sum_total_amount && $campaign->get_campaign_report_sum_no_of_clicks)? number_format($campaign->get_campaign_report_sum_total_amount/$campaign->get_campaign_report_sum_no_of_clicks,2) : 0 }} </td>
              <td> {{ ($campaign->get_campaign_report_sum_no_of_clicks)? $campaign->get_campaign_report_sum_no_of_clicks : 0 }} </td>
              <td> {{ $campaign->remaing_daily }} </td>
              <td style="">{{ $campaign->landing_url }}</td>
              <td>
                @if($campaign->admin_approval == 0)
                <span class="text-warning">Pending</span>
                @elseif($campaign->admin_approval == 1) <span class="text-success">Approved</span>
                @elseif($campaign->admin_approval == 2) <span class="text-danger ">Decline</span>
                @endif
              </td>
              <td>
                @if($campaign->campaign_status == 0)
                <span class="text-warning">Pause</span>
                @elseif($campaign->campaign_status == 1) <span class="text-success">Running</span>
                @elseif($campaign->campaign_status == 2) <span class="text-danger">Stop</span>
                @endif
              </td>
              <td>
                @php
                $campaign_id = encrypt($campaign->id);
                @endphp
                <button type="button" class="btn btn-primary @if($campaign->campaign_status == 1)disable @endif btn-sm">
                  <a href="javascript:void(0);" class="text-white" onClick="change_campaign_status({{ $campaign->id }},1)">Play</a>
                </button>
                <button type="button" class="btn btn-dark @if($campaign->campaign_status == 0)disable @endif btn-sm">
                  <a href="javascript:void(0);" class="text-white" onClick="change_campaign_status({{ $campaign->id }},0)">Pause</a>
                </button>

                <button type="button" class="btn btn-secondary @if($campaign->campaign_status == 2)disable @endif btn-sm">
                  <a href="javascript:void(0);" class="text-white" onClick="change_campaign_status({{ $campaign->id }},2)">Stop</a>
                </button>

                <button type="button" class="btn btn-warning btn-sm">
                  <a href="{{ URL('/advertiser/campaigns/edit/'.$campaign_id )}}" class="text-white">Edit</a>
                </button>

                <button type="submit" class="btn btn-danger btn-sm">
                  <a href="{{ URL('/advertiser/campaigns/delete/'.$campaign_id )}}" class="text-white">Delete</a>
                </button>
              </td>
            </tr>
            @php $i++ @endphp
            @empty
            <tr>
              <td>No Campaigns exist!</td>
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