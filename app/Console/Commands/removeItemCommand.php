<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class removeItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-item
                            {order_id : In which order to remove a item}
                            {item_id : Which item to remove from order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove item from order';

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
                    $fail('Order id = ' . $value . ' has ' . $order->status  . ' status, but only created status allowed');
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

        if ($itemOrder && $itemOrder->pivot->count > 1) {

            $itemOrder->pivot->decrement('count');

            $this->info('Item with id = <fg=red><bg=black>' . $this->argument('item_id') . '</></> removed. Now its <fg=red><bg=black>' . $itemOrder->pivot->count . '</></> of them in order id =  <fg=red><bg=black>' . $order->id . '</></>');
        } else {
            $order->items()->detach($this->argument('item_id'));

            $this->info('Item with id = <fg=red><bg=black>' . $this->argument('item_id') . '</></> removed from order id = <fg=red><bg=black>' . $order->id . '</></>');
        }

    }
}