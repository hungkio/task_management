<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return route('admin.dashboards');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user->update([
           'is_online' => 0
        ]);

        if($roleName == 'editor') {
            $tasks_doing = Tasks::where('editor_id', auth()->id())->whereDate('created_at', Carbon::today())->whereIn('status', [Tasks::EDITING, Tasks::TODO, Tasks::REJECTED])->get();
            foreach ($tasks_doing as $task) {
                $task->update([
                    'editor_id' => null,
                    'status' => Tasks::WAITING,
                    'start_at' => null
                ]);
            }
        }

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
