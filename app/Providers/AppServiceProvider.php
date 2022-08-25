<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiSuccess', function ($data, $meta = [], $statusCode = 200) {
            return response()->json([
                'status' => $statusCode,
                'data' => $data,
                'meta' => $meta
            ]);
        });

        Response::macro('apiError', function ($message, $errors = [], $statusCode = 401, $code = HttpResponse::HTTP_BAD_REQUEST) {
            return response()->json([
                'status' => $statusCode,
                'message' => $message,
                'errors' => $errors,
            ])->setStatusCode($code);
        });
    }
}
