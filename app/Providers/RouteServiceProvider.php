<?php

namespace App\Providers;

use App\Exceptions\ItemNotFoundException;
use App\Exceptions\OrderNotFoundException;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use mysql_xdevapi\Exception;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('order',function ($order){
            if ($order = Order::find($order)){
                return $order;
            }
            throw new ModelNotFoundException('Order not found');
        });

        Route::bind('item',function ($item){
            if ($item = Item::find($item)){
                return $item;
            }
            throw new ModelNotFoundException('Item not found');
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiV1Routes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api_v1.php'));
    }
}
