<?php

namespace App\Console\Commands;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Console\Command;

class showOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show-order
                            {order_id : In which order to add item}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show order with items';

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
        ], [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ], [
            'order_id.exists' =>  'No such order with id = ' . $this->argument('order_id'),
        ]);


        if ($validator->fails()) {
            $this->error('Command cannot be executed:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }


        $order = Order::find($this->argument('order_id'));

        $this->info(json_encode($order));
        foreach ($order->items as $item) {
            $this->info(json_encode($item));
        }
    }
}