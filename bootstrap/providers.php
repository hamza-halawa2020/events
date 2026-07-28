<?php

use App\Providers\AdminPanelProvider;
use App\Providers\AppPanelProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    AppPanelProvider::class,
];
