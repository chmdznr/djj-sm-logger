<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    // RouteServiceProvider is intentionally NOT registered:
    // routes are now declared via Application::configure()->withRouting(...) in bootstrap/app.php.
];
