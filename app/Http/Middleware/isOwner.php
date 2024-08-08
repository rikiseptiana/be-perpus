<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Role;

class isOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->role->name === 'owner') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized. anda buka owner!'], 403);
    }
        
        // $user = auth()->user();
        // $isOwner = Role::where('name', 'owner')->first();
        // if ($user->role_id == $isOwner->id) {
        //     // return $next($request);
        //     return response()->json(['message' => 'Unauthorized, Anda bukan Owner!'], 401);
        // }

        // return response()->json(['message' => 'Unauthorized, Anda bukan Owner!'], 401);

        
    
}
