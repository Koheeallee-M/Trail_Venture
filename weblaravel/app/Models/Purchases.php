<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchases extends Model
{
    use HasFactory;

    protected $primaryKey = 'pur_id';
    protected $fillable = ['cust_id', 'date', 'total'];

    public function customers()
    {
        return $this->belongsTo(Customers::class, 'cust_id', 'cust_id');
    }

    public function purchasesDetails()
    {
        return $this->hasMany(PurchasesDetails::class, 'pur_id', 'pur_id');
    }
}

