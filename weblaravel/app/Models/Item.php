<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    protected $fillable = ['item_name', 'list_price', 'description', 'qtyInStock'];

    public function purchasesDetails()
    {
        return $this->hasMany(PurchasesDetails::class, 'item_id', 'item_id');
    }
}

