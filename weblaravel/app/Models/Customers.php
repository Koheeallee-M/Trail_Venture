<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers'; // Optional if your table is already named correctly
    protected $primaryKey = 'cust_id';

    public $timestamps = true; // or false depending on your table
    public $incrementing = true; // or false if you use UUIDs
    protected $keyType = 'int'; // or 'string' for UUIDs

    protected $fillable = ['name', 'email'];

    public function getRouteKeyName()
    {
        return 'cust_id';
    }

    public function purchases()
    {
        return $this->hasMany(Purchases::class, 'cust_id', 'cust_id');
    }

    public function purchasesDetails()
    {
        return $this->hasMany(PurchasesDetails::class, 'cust_id', 'cust_id');
    }
}

