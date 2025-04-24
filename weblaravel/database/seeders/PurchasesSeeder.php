<?php

// database/seeders/PurchaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class PurchasesSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();

        foreach ($customers as $cust) {
            $purchasesCount = rand(1, 3); // Each customer can make 1–3 purchases
            for ($i = 0; $i < $purchasesCount; $i++) {
                DB::table('purchases')->insert([
                    'cust_id'    => $cust->id,
                    'date'       => now()->subDays(rand(0, 30))->toDateString(),
                    'total'      => 0, // updated later in purchase_details
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}


