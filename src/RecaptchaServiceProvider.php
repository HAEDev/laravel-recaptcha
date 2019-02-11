<?php namespace HAEDev\Recaptcha;

use Illuminate\Support\ServiceProvider;
use Blade;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        
        Blade::directive('recaptcha', function() {
            $siteKey = config('recaptcha.site_key');
            return "<?php echo '<div class=\"g-recaptcha\" data-sitekey=\"{$siteKey}\"></div><script src=\"https://www.google.com/recaptcha/api.js\"></script>'; ?>";
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/recaptcha.php', 'recaptcha');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['recaptcha'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__. '/../config/recaptcha.php' => config_path('recaptcha.php'),
        ], 'config');
    }
}
