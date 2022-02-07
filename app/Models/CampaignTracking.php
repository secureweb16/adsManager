<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CampaignFund;
use App\Models\PublisherReport;

class CampaignTracking extends Model
{
    use HasFactory;

    public function publisher_payment(){
    	return $this->hasOne(PublisherReport::class,'campaign_id','campaign_id');
    }

}
