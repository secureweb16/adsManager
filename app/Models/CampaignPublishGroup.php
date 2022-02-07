<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramGroup;

class CampaignPublishGroup extends Model
{
    use HasFactory;

    public function telegramGroup(){
    	return $this->hasOne(TelegramGroup::class,'id','telegram_group_id');
    }
}
