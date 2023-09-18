<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admins')->except('logout');
    }

    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function redirectTo(): string
    {
        $permissions = Auth::user()->getAllPermissions();
        $route = route('admin.dashboards');
        session()->remove('url.intended');
        foreach ($permissions as $permission) {
            if ($permission->name == 'tasks.view') {
                $route = route('admin.tasks.index');
            } elseif ($permission->name == 'dashboards.view') {
                $route = route('admin.dashboards');
            } elseif ($permission->name == 'dbchecks.view') {
                $route = route('admin.dbcheck_tasks.index');
            } elseif ($permission->name == 'customer.view') {
                $route = route('admin.customer_tasks.index');
            }
        }
        return $route;
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'is_online' => 0
        ]);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('admin.login');
    }
}
