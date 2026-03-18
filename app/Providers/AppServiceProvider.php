<?php

namespace App\Providers;

use App\Actions\ValidateProductStock;
use App\Contract\CartServiceInterface;
use App\Models\User;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Number::useCurrency('IDR');

        Gate::define('stock_availability', function (User $user) {
            try {
                ValidateProductStock::run();

                return true;
            } catch (ValidationException $e) {
                session()->flash('error', $e->getMessage());

                return false;
            }
        });
    }
}
