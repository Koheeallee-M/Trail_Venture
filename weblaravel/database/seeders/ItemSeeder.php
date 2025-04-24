<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            [
              'item_name'=>'Rope',
              'list_price'=>9.99,
              'description'=>'A cheap rope',
              'qtyInStock'=>100
            ],
            [
                'item_name'=>'Boots',
                'list_price'=>29.99,
                'description'=>'Robust boots',
                'qtyInStock'=>200
            ],
            [
                'item_name'=>'Tent',
                'list_price'=>49.99,
                'description'=>'A tent for camping',
                'qtyInStock'=>50
            ],
            [
                'item_name'=>'Sleeping Bag',
                'list_price'=>19.99,
                'description'=>'A warm sleeping bag',
                'qtyInStock'=>75
            ],
            [
                'item_name'=>'Flashlight',
                'list_price'=>14.99,
                'description'=>'A bright flashlight',
                'qtyInStock'=>150
            ]  
        ]);
    }
}

