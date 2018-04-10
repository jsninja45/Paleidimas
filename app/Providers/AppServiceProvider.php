<?php namespace App\Providers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$loader = AliasLoader::getInstance();

		if ($this->app->environment() === 'local') {
			$loader->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
		}
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);

		if ($this->app->environment() === 'local') {
			$this->app->register('Barryvdh\Debugbar\ServiceProvider');
		}
	}

}
