<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PreTaskController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\VerificationController;
use App\Http\Controllers\Admin\UploadTinymceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ReportController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Registration Routes...
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Password Confirmation Routes...
    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

    // Email Verification Routes...
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/croncase', function () {
        \Illuminate\Support\Facades\Artisan::call('case:get');
    });
    // Route Dashboards
    Route::middleware('auth')
        ->group(function () {
            // dashboard
            Route::get('/', [DashboardController::class, 'index'])->name('dashboards');

            Route::get('/assign-editor', [DashboardController::class, 'assignEditor'])->name('assign-editor');
            Route::get('/assign-qa/{id}', [DashboardController::class, 'assignQA'])->name('assign-qa');
            Route::get('/popup/{id}', [DashboardController::class, 'showPopup'])->name('popup');
            Route::post('/popup/{id}', [DashboardController::class, 'savePopup'])->name('popup.save');
            Route::post('/update-status', [DashboardController::class, 'updateStatus'])->name('update-status');
            Route::get('/check-online/{id}', [DashboardController::class, 'checkOnline'])->name('check-online');

            //task
            Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
            Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
            Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
            Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
            Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
            Route::get('tasks/cron', [TaskController::class, 'cron'])->name('tasks.cron');
            Route::post('tasks/import', [TaskController::class, 'import'])->name('tasks.import');
            Route::post('/tasks/bulk-delete', [TaskController::class, 'bulkDelete'])->name('tasks.bulk-delete');
            Route::get('tasks/{task}/clone', [TaskController::class, 'clone'])->name('tasks.clone');

            //pre task
            Route::get('pre_tasks', [PreTaskController::class, 'index'])->name('pre_tasks.index');
            Route::get('pre_tasks/create', [PreTaskController::class, 'create'])->name('pre_tasks.create');
            Route::post('pre_tasks', [PreTaskController::class, 'store'])->name('pre_tasks.store');
            Route::get('pre_tasks/{preTask}/edit', [PreTaskController::class, 'edit'])->name('pre_tasks.edit');
            Route::delete('pre_tasks/{preTask}', [PreTaskController::class, 'destroy'])->name('pre_tasks.destroy');
            Route::put('pre_tasks/{preTask}', [PreTaskController::class, 'update'])->name('pre_tasks.update');
            Route::post('/pre_tasks/bulk-delete', [PreTaskController::class, 'bulkDelete'])->name('pre_tasks.bulk-delete');

            //reports
            Route::get('report/month', [ReportController::class, 'month'])->name('reports.month');
            Route::get('report/customer', [ReportController::class, 'customer'])->name('reports.customer');
            Route::get('report/employee', [ReportController::class, 'employee'])->name('reports.employee');
            Route::post('report/filter-by-date', [ReportController::class, 'getTasksByDate'])->name('reports.filter-by-date');
            Route::get('report/salary', [ReportController::class, 'salary'])->name('reports.salary');
            Route::get('report/user_salary/{user_id?}', [ReportController::class, 'user_salary'])->name('reports.user_salary');

            //Upload Tinymce
            Route::post('uploads-tinymce', UploadTinymceController::class)->name('public.upload-tinymce');

            // System Route
            Route::post('/admins/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admins.bulk-delete');
            Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
            Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
            Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
            Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
            Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');

            //roles
            Route::post('/roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });
    Route::get('test_auth_dropbox', function () {
//                $refreshToken = config('dropbox.refreshToken');
//                $clientId = config('dropbox.clientId');
//                $clientSecret = config('dropbox.clientSecret');
//                $curl = curl_init();
//
//                curl_setopt_array($curl, array(
//                    CURLOPT_URL => 'https://api.dropbox.com/oauth2/token',
//                    CURLOPT_RETURNTRANSFER => true,
//                    CURLOPT_ENCODING => '',
//                    CURLOPT_MAXREDIRS => 10,
//                    CURLOPT_TIMEOUT => 0,
//                    CURLOPT_FOLLOWLOCATION => true,
//                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                    CURLOPT_CUSTOMREQUEST => 'POST',
//                    CURLOPT_POSTFIELDS => "refresh_token=$refreshToken&grant_type=refresh_token&client_id=$clientId&client_secret=$clientSecret",
//                    CURLOPT_HTTPHEADER => array(
//                        'Content-Type: application/x-www-form-urlencoded',
//                    ),
//                ));
//
//                $response = curl_exec($curl);
//
//                curl_close($curl);
//                dd($response);

    });

    Route::get('test_list_folder', function () {
//                $ch = \curl_init();
//                $payload = '{
//                "include_deleted": false,
//                "include_has_explicit_shared_members": false,
//                "include_media_info": false,
//                "include_mounted_folders": true,
//                "include_non_downloadable_files": true,
//                "path": "/1.Working",
//                "recursive": false
//                }';
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_HTTPHEADER, [
//                    'Authorization: Bearer sl.BePhjgDVVrIGH2vuCYT9R09UxsIXgZLFdi7UWBX2Fx-fSpBK5JG1EzQ0JzxKtV_d4iNB-9sQSes2p9T75yDVfv-fuB9sMqBccdHcLk8Dj11cdEiG3DbWBmJb6ESAH7eO1F6dXK6A',
//                    'Content-Type: application/json'
//                ]);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//                curl_setopt($ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/list_folder');
//
//                $result = curl_exec($ch);
//                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//
//                curl_close($ch);
//                dd($result, $httpcode);

        $accessToken = 'sl.BeNdhnLOZXbdjDnY85WEwjJkOyWyydoLqXd5vUezwCTarpY7Z1UtyeetnSMBITPwOzdqzLuRna275sDUcIKb_Gvth-VMxTYZIsvQF7p4fFnBgb3sKEdBhGKHqw7aF4nTEm3oXXqU';
        $client = new Spatie\Dropbox\Client($accessToken);
        dd($client->listFolder('1.Working'));
    });

    Route::get('test_upload', function () {
        $content = file_get_contents(storage_path('app/settings.json'));

        $ch = \curl_init();
        $fileName = date('Y-m-d') . "-settings.json";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer sl.BeIdp8ZC6Y6HYP8ash_WQ-U9JIm98gmClqYho4WV4xXI14Lgneksr7DEL9hZv6wksx_x2IZcK_UUAVtj_lHeIY9IdocLvgfCR8i7PAG9jWGD-0JVMMveFNMNmbOBPrqAlNRiSKLA',
            'Content-Type: application/octet-stream',
            "Dropbox-API-Arg: {\"path\": \"/$fileName\",\"mode\": \"overwrite\",\"autorename\": false,\"mute\": false,\"strict_conflict\": false}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/upload');

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        dd($result);
    });

});
