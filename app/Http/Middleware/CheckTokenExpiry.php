<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $token = $request->user()->currentAccessToken();

            // Kiểm tra nếu token đã hết hạn
            if ($token->expires_at && Carbon::now()->greaterThan($token->expires_at)) {
                $token->delete();

                return response()->json(['success' => false, 'message' => 'Token đã hết hạn, vui lòng đăng nhập lại.'], 401);
            }
        }

        return $next($request);
    }
}
