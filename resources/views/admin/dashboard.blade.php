@extends('layouts.admin')
@section('content')

        <!-- top tiles -->
        <div class="dashboarddiv" style="display: inline-block;" >
        <div class="tile_count">
        <!-- top tiles -->
        <div class="row" >                
               <div class="col-md-12">
                <form method="get" id="mydateform">          
                  <div class="row input-daterange">
                      <div class="col-md-12">
                        <div class="d-flex">
                          <div class="selectdate">
                            <input name="daterange" id="reportrangeadm" type="text" class="form-control" readonly="" value="{{  $alldata['feildVal'] }}">
                          </div>                      
                          <div class="filterbtn d-flex">
                            <input type="submit" name="filter" id="submit" class="btn btn-primary" value="Filter">
                            <button type="button" class="btn btn-warning">
                              <a href="/admin/dashboard" class="text-white"> Reset </a>
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
         <div class="row" >                
            <div class="col-md-3 col-sm-4 ">
              <div class="tile_count_inr">
                <span class="count_top"><i class="fa fa-user"></i>Revenue </span>
                <div class="count">${{$alldata['total_revenue']}}</div>
              </div>
            </div> 
            <div class="col-md-3 col-sm-4 ">
              <div class="tile_count_inr">
                <span class="count_top"><i class="fa fa-user"></i>Balances</span><br>
                <ul class="twocollist">
                  <li>
                    <span class="count_top"></i>Token Reflections</span>
                    <div class="count">${{$alldata['token_reflection']}}</div>
                  </li>
                  <li>
                    <span class="count_top"></i>NFT Reflections</span>
                    <div class="count">${{$alldata['NFT_reflection']}}</div>                   
                  </li>
                </ul>
              </div>
            </div> 

            <div class="col-md-3 col-sm-4 ">
              <div class="tile_count_inr">
                <span class="count_top"><i class="fa fa-user"></i> clicks</span>
                <div class="count">{{$alldata['clicks']}}</div>
              </div>
            </div> 
            <div class="col-md-3 col-sm-4 ">
              <div class="tile_count_inr">
                <span class="count_top"><i class="fa fa-user"></i>Average CPC</span>
                <div class="count">${{$alldata['avg_cpc']}}</div>
              </div>
            </div> 
          </div>   
            <div class="row" >    
              <div class="col-md-3 col-sm-4 ">
                <div class="tile_count_inr">
                  <span class="count_top"><i class="fa fa-user"></i> Active Advertiser</span>
                  <div class="count orange">{{$alldata['advertiser_active']}}</div>
                </div>
              </div> 
              <div class="col-md-3 col-sm-4 ">
                <div class="tile_count_inr">
                  <span class="count_top"><i class="fa fa-user"></i> Active Publishers</span>
                  <div class="count orange">{{$alldata['publisher_active']}}</div>
                </div>
              </div>
              <div class="col-md-3 col-sm-4 ">
                <div class="tile_count_inr">
                  <span class="count_top"><i class="fa fa-user"></i> Active Campaigns</span>
                  <div class="count orange">{{$alldata['campaign_active']}}</div>
                </div>
              </div> 
              <div class="col-md-3 col-sm-4 ">
                <div class="tile_count_inr">
                  <span class="count_top"><i class="fa fa-user"></i>Campaigns in Review</span>
                  <div class="count redshade">{{$alldata['campaign_inactive']}}</div>
                </div>
              </div>
            </div>
        </div>
        </div>
       


@endsection