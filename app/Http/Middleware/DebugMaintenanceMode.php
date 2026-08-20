<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DebugMaintenanceMode extends PreventRequestsDuringMaintenance
{
    public function handle($request, \Closure $next)
    {
        $path = $request->path();
        $isExcluded = $this->inExceptArray($request);
        Log::info('DebugMaintenanceMode check', [
            'path' => $path,
            'isExcluded' => $isExcluded,
            'neverPrevent' => static::$neverPrevent,
            'except' => $this->except,
            'getExcludedPaths' => $this->getExcludedPaths(),
        ]);
        return parent::handle($request, $next);
    }
}
