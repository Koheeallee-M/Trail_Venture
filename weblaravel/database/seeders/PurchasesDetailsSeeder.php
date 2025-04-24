<?php

// database/seeders/PurchaseDetailsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Purchases;
use App\Models\Item;

class PurchasesDetailsSeeder extends Seeder
{
    public function run()
    {
        $purchases = Purchases::all();
        $items = Item::all();

        foreach ($purchases as $purchases) {
            $itemsUsed = $items->random(rand(1, 3)); // 1–3 items per purchase
            $total = 0;

            foreach ($itemsUsed as $item) {
                $qty = rand(1, 5);
                $price = $item->list_price;
                $lineTotal = $qty * $price;
                $total += $lineTotal;

                // insert into purchases_details
                DB::table('purchases_details')->insertOrIgnore([
                    'pur_id'     => $purchases->pur_id,
                    'cust_id'    => $purchases->cust_id,
                    'item_id'    => $item->item_id,
                    'item_name'  => $item->item_name,
                    'price_paid' => $price,
                    'qty'        => $qty,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // update total in purchases table
            DB::table('purchases')
                ->where('pur_id', $purchases->pur_id)
                ->update(['total' => $total]);
        }
    }
}


