<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PublisherReport;

class TelegramGroup extends Model
{
    use HasFactory;   
    
    public function TelegrmGroupDay(){
        return $this->hasMany(TelegramTiming::class,'telegram_group_id','id');
    }

    public function groups_earnings(){
        return $this->hasMany(PublisherReport::class,'group_id','id');
    }
}
