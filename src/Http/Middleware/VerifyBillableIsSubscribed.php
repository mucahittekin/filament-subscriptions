<?php

namespace TomatoPHP\FilamentSubscriptions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use TomatoPHP\FilamentSubscriptions\Facades\FilamentSubscriptions;
use TomatoPHP\FilamentSubscriptions\Services\FilamentSubscriptionServices;

class VerifyBillableIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subscriber = FilamentSubscriptionServices::getSubscriber();

        if ($subscriber && $subscriber->activePlanSubscriptions()->isEmpty()) {
            if(filament()->getTenant()){
                return redirect()->route('filament.'.filament()->getCurrentPanel()->getId().'.tenant.billing', ['tenant'=> filament()->getTenant()->{filament()->getCurrentPanel()->getTenantSlugAttribute()}]);
            }
            else {
                return redirect()->route('filament.'.filament()->getCurrentPanel()->getId().'.tenant.billing');
            }
        }

        return $next($request);
    }
}
