<?php

namespace App\Http\Middleware;

use App\Models\Treatment;
use Closure;
use Illuminate\Http\Request;

class ValidatePatientTreatment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $treatmentUuid = $request->headers->get('Treatment-Uuid');
        // if ($treatmentUuid) {
        //     $treatmentExists = Treatment::where('uuid', $treatmentUuid)->exists();
        //     if ($treatmentExists) {
                return $next($request);
        //     }
        // }

        // return response()->json([
        //     'message' => 'Invalid treatment'
        // ], 404);
    }
}
