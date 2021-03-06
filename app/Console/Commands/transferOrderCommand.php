<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class transferOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer-order
                            {order_id : Which order to transfer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer an order';

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
            'order_id' => ['required', 'integer', 'exists:orders,id', function ($attribute, $value, $fail) {
                $order = Order::find($value);
                if ($order && $order->status != 'processed') {
                    $fail('Order id = ' . $value . ' has «' . $order->status  . '» status, but only «processed» status allowed');
                }}]
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
        $oldStatus = $order->status;
        $order->update(['status' => 'transferred']);

        $this->info('Order with id = <fg=red><bg=black>' . $this->argument('order_id') . '</></> has changed status from <fg=red><bg=black>«' . $oldStatus . '»</></> to <fg=red><bg=black>«' . $order->status . '»</></>');
    }
}
