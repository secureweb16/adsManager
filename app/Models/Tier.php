<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TierPublisher;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tier extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function get_publisher(){
        return $this->hasMany(TierPublisher::class,'tier_id','id');
    }
}
