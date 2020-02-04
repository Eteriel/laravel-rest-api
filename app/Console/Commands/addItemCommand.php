<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class addItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-item
                            {order_id : In which order to add item}
                            {item_id : Which item to add in order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add item to order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $validator = \Validator::make([
            'order_id' => $this->argument('order_id'),
            'item_id' => $this->argument('item_id'),
        ], [
            'order_id' => ['required', 'integer', 'exists:orders,id', function ($attribute, $value, $fail) {
                $order = Order::find($value);
                if ($order && $order->status != 'created') {
                    $fail('Order id = ' . $value . ' has «' . $order->status  . '» status, but only «created» status allowed');
                }}],
            'item_id' => ['required', 'integer', 'exists:items,id']
        ], [
            'order_id.exists' =>  'No such order with id = ' . $this->argument('order_id'),
            'item_id.exists' =>  'No such item with id = ' . $this->argument('item_id')
        ]);


        if ($validator->fails()) {
            $this->error('Command cannot be executed:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        $order = Order::find($this->argument('order_id'));
        $itemOrder = $order->items->firstWhere('id', $this->argument('item_id'));

        if ($itemOrder) {
            $itemOrder->pivot->increment('count');

            $this->info('Item with id = <fg=red><bg=black>' . $this->argument('item_id') . '</></> added. Now its <fg=red><bg=black>' . $itemOrder->pivot->count . '</></> of them in order id =  <fg=red><bg=black>' . $order->id . '</></>');
        } else {
            $order->items()->attach($this->argument('item_id'));

            $this->info('Item with id = <fg=red><bg=black>' . $this->argument('item_id') . '</></> added to order id = <fg=red><bg=black>' . $order->id . '</></>');
        }

        return true;
    }
}
