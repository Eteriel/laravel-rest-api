<?php

use Illuminate\Database\Seeder;

class ItemOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = \DB::table('orders')->first();
        $item1 = \DB::table('items')->where('name', 'table')->first();
        $item2 = \DB::table('items')->where('name', 'chair')->first();
        \DB::table('item_order')->insert(['order_id' => $order->id, 'item_id' => $item1->id, 'created_at' =>  \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
        \DB::table('item_order')->insert(['order_id' => $order->id, 'item_id' => $item2->id, 'created_at' =>  \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
    }
}
