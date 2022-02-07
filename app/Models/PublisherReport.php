<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;
use App\Models\User;

class PublisherReport extends Model
{
    
    public function campaigndata(){
		return $this->hasOne(Campaign::class,'id','campaign_id');
	}
	
	public function publisher_data(){
		return $this->hasOne(User::class,'id','publisher_id');
	}

	public function get_campaignss(){
		return $this->belongsTo(Campaign::class,'campaign_id','id');
	}


	
}
