<?php

namespace App\Http;

use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        'role' => EnsureRole::class,
    ];
}
