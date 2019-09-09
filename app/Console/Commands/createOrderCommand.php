<?php

namespace App\Console\Commands;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Console\Command;

class createOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create order';

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

        $order = Order::create(['status' => 'created']);

        $this->info(json_encode($order));
    }
}