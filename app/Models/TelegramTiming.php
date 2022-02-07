<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramTiming extends Model
{
    use HasFactory;

    public function get_campaignsss(){
        return $this->belongsTo(TelegramGroup::class,'telegram_group_id','id');
    }

}
