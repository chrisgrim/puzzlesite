<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitcoinTransaction extends Model
{
    protected $fillable = ['order_id', 'bitcoin_address', 'amount', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}