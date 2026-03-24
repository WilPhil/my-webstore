<?php

namespace App\Providers;

use App\Actions\ValidateProductStock;
use App\Contract\CartServiceInterface;
use App\Models\User;
use App\Services\LocationQueryService;
use App\Services\SessionCartService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartServiceInterface::class, SessionCartService::class);
        $this->app->bind(LocationQueryService::class, LocationQueryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // All model data can be mass assignment
        Model::unguard();

        // All number currency will be IDR formatted
        Number::useCurrency('IDR');

        // Gate for checkout
        Gate::define('stock_availability', function (User $user) {
            try {
                ValidateProductStock::run();

                return true;
            } catch (ValidationException $e) {
                session()->flash('error', $e->getMessage());

                return false;
            }
        });

        // Gate for checkout also
        Gate::define('empty_cart', function (CartServiceInterface $cart) {
            try {
                $cart_quantity = $cart->getAllItem()->totalQuantity;

                return $cart_quantity > 0;
            } catch (ValidationException $e) {
                session()->flash('error', $e->getMessage());

                return false;
            }
        });

        Model::preventLazyLoading(! app()->isProduction());
    }
}
