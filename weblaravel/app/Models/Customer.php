<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    public function purchases()
    {
        return $this->hasMany(Purchases::class, 'cust_id', 'cust_id');
    }

    public function purchasesDetails()
    {
        return $this->hasMany(PurchasesDetails::class, 'cust_id', 'cust_id');
    }
}

