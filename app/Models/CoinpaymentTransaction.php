<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FundsAdvertisersLogs;

class CoinpaymentTransaction extends Model
{
    use HasFactory;
    protected $table = 'coinpayment_transactions';

    public function payment_update(){
    	return $this->hasMany(FundsAdvertisersLogs::class,'txn_id','txn_id');
    }
}
