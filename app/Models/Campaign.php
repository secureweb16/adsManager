<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
	use HasFactory;
	use SoftDeletes;

	public function advertiserData(){
		return $this->hasOne(User::class,'id','advertiser_id');
	}


    public function get_campaign_report(){
		return $this->hasMany(PublisherReport::class,'campaign_id','id');
	}

}
