<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitcoinTransaction extends Model
{
    protected $fillable = [ 'order_id', 'bitcoin_address', 'amount', 'status', 'webhook_id', 'webhook_url', 'confirmations' ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}