<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;
use App\Models\User;
use App\Models\PublisherAccount;

class PublisherPayment extends Model
{
	public function publisher_data(){
		return $this->hasOne(User::class,'id','publisher_id');
	}

	public function publisher_account(){
    	return $this->hasOne(PublisherAccount::class,'user_id','publisher_id');
    }
}
