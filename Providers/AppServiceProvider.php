<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\KeyValue;
use App\Models\Menu;
use App\Models\Page;
use App\Observers\ArticleObserver;
use App\Observers\MenuObserver;
use App\Observers\SettingObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::withoutDoubleEncoding();
        Article::observe(ArticleObserver::class);
        Page::observe(ArticleObserver::class);
        Gallery::observe(ArticleObserver::class);
        KeyValue::observe(SettingObserver::class);
        Menu::observe(MenuObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Illuminate\Support\ServiceProvider::class);
            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
            $this->app->register(\Orangehill\Iseed\IseedServiceProvider::class);
        }
    }
}
