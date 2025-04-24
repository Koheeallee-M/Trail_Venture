<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasesDetails extends Model
{
    use HasFactory;

    protected $table = 'purchases_details'; 
    public $incrementing = false; // composite keys = no increment
    protected $fillable = ['pur_id', 'cust_id', 'item_id', 'item_name', 'price_paid', 'qty'];

    public function purchases()
    {
        return $this->belongsTo(Purchases::class, 'pur_id', 'pur_id');
    }

    public function customers()
    {
        return $this->belongsTo(Customers::class, 'cust_id', 'cust_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}

