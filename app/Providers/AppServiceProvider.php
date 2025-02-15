<?php

namespace App\Providers;

use App\Domain\Contract\ICategoryRepository;
use App\Domain\Contract\IOrderItemRepository;
use App\Domain\Contract\IOrderRepository;
use App\Domain\Contract\IProductRepository;
use App\Domain\Contract\IUserRepository;
use App\Infra\Repositories\CategoryRepository;
use App\Infra\Repositories\OrderItemRepository;
use App\Infra\Repositories\OrderRepository;
use App\Infra\Repositories\ProductRepository;
use App\Infra\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IOrderitemRepository::class, OrderItemRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->roles->contains('name', 'admin') ? true : null;
        });

        Gate::define('view-products', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'view-products');
        });

        Gate::define('edit-products', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'edit-products');
        });

        Gate::define('view-categories', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'view-categories');
        });

        Gate::define('view-orders', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'view-orders');
        });

        Gate::define('update-orders', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'update-orders');
        });

        Gate::define('manage-order-items', function ($user) {
            return $user->roles->contains('name', 'manager') || $user->permissions->contains('name', 'manage-order-items');
        });


        Gate::define('create-orders', function ($user) {
            return $user->roles->contains('name', 'basic_user') || $user->permissions->contains('name', 'create-orders');
        });

        Gate::define('view-products', function ($user) {
            return $user->roles->contains('name', 'basic_user') || $user->permissions->contains('name', 'view-products');
        });

        Gate::define('view-categories', function ($user) {
            return $user->roles->contains('name', 'basic_user') || $user->permissions->contains('name', 'view-categories');
        });

        Gate::define('view-orders', function ($user) {
            return $user->roles->contains('name', 'basic_user') || $user->permissions->contains('name', 'view-orders');
        });
    }
}
