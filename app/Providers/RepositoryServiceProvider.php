<?php

namespace App\Providers;

// In RepositoryServiceProvider.php

use App\Repositories\TaskRepository;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ...
    }
}
