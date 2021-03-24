<?php

namespace App\Http\Middleware;

use App\Models\Common;
use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = '')
    {
        if ($request->user()->type == Common::TYPE_XM) {
            return $next($request);
        }

        if ($role) {
            $roles = explode(':', $role);
            $flag = 0;
            $permissionArr = Common::getPermissionList($request->user());

            foreach ($roles as $r) {
                if (in_array($r, $permissionArr)) {
                    $flag = 1;
                    break;
                }
            }

            if (!$flag) {
                return response()->json([
                    'code' => 403,
                    'msg' => '没有权限',
                    'dat' => []
                ], 403);
            }
        }

        return $next($request);
    }
}
