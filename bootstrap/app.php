<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        // $middleware->validateCsrfTokens();
      
        // $middleware->append(StartSession::class); //ensures the session starts on every request
       
        // $middleware->validateCsrfTokens();
        // $middleware->append(EnsureFrontendRequestsAreStateful::class); //ensure stateful    //removed this for stateless
         // $middleware->append(AuthenticateSession::class); //keeps making requests, the session will stay active indefinitely.

         $middleware->group('web', [
            StartSession::class,  // Ensures session handling
            // ShareErrorsFromSession::class,  // Shares validation errors between requests
            // \App\Http\Middleware\VerifyCsrfToken::class,   // use web except login etc
            \App\Http\Middleware\VerifyCSRFToken::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // SubstituteBindings::class,  // Enables route model binding
            // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Handles cookies
            // \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class, // Supports HTMX/Livewire
        ]);
        

        $middleware->group('api', [
            \Illuminate\Session\Middleware\StartSession::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,// Needed for cookie-based authentication
            // \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // except login etc
            \App\Http\Middleware\VerifyCSRFToken::class,
            \App\Http\Middleware\RefreshSanctumToken::class, //refresh the session because auth sanctum does not automatically reset the session unlike web (to refresh not to logout)
            // 'throttle:api',
            // SubstituteBindings::class,
        ]);
    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
