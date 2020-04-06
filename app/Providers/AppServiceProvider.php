<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:41
 */

namespace OneSite\Notify\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use OneSite\Notify\Console\Commands\Test;
use OneSite\Notify\Services\Common\Notify;
use OneSite\Notify\Services\Contract\Notification;
use OneSite\Notify\Services\Firebase;


class AppServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    private $namespace = null;

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [

    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */

    public $singletons = [
        Notification::class => Firebase::class
    ];

    /**
     * AppServiceProvider constructor.
     */
    public function __construct()
    {
        $this->singletons = Notify::singletons();

        $this->setNamespace();
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return !empty($this->namespace) ? $this->namespace : __NAMESPACE__;
    }

    /**
     *
     */
    public function setNamespace(): void
    {
        $this->namespace = str_replace('\Providers', '', __NAMESPACE__);
    }

    /**
     * sudo php artisan vendor:publish
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../configs' => config_path(''),
        ], 'notify-configs');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
            __DIR__ . '/../../database/seeds' => database_path('seeds')
        ], 'notify-migrations');

        $this->publishes([
            __DIR__ . '/../../resources/notify-tests' => resource_path('notify-tests')
        ], 'notify-resources');
    }

    /**
     *
     */
    public function register()
    {
        $this->registerHelpers();
        $this->registerRoutes();
        $this->registerCommands();
    }

    /**
     *
     */
    private function registerHelpers()
    {
        $helpers = __DIR__ . '/../../helpers.php';

        if (file_exists($helpers)) {
            require $helpers;
        }
    }

    /**
     *
     */
    private function registerRoutes()
    {
        $routes = __DIR__ . '/../../route.php';

        if (!file_exists($routes)) {
            return;
        }

        $namespace = $this->getNamespace() . '\Http\Controllers';

        Route::middleware('web')
            ->namespace($namespace)
            ->group($routes);
    }

    /**
     *
     */
    private function registerCommands()
    {
        $this->commands([
            Test::class
        ]);
    }
}
