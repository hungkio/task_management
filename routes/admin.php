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

use App\Http\Controllers\DesignController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProduceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\VerificationController;
use App\Http\Controllers\Admin\UploadTinymceController;
use Illuminate\Support\Facades\Storage;

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

    // Route Dashboards
    Route::middleware('auth')
        ->group(function () {
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
                $client = getDropboxClient();
                dd($client->listFolder('1.Working'));
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
            // design
            Route::get('/', [DesignController::class, 'index'])->name('designs.index');
            Route::post('/bulk-delete', [DesignController::class, 'bulkDelete'])->name('designs.bulk-delete');
            Route::get('/create', [DesignController::class, 'create'])->name('designs.create');
            Route::post('/', [DesignController::class, 'store'])->name('designs.store');
            Route::get('/{design}/edit', [DesignController::class, 'edit'])->name('designs.edit');
            Route::put('/{design}/updateStatus', [DesignController::class, 'updateStatus'])->name('designs.updateStatus');
            Route::delete('/{design}', [DesignController::class, 'destroy'])->name('designs.destroy');
            Route::put('/{design}', [DesignController::class, 'update'])->name('designs.update');
            Route::post('/{design}/status', [DesignController::class, 'changeStatus'])->name('designs.change.status');
            Route::post('/bulk-status', [DesignController::class, 'bulkStatus'])->name('designs.bulk.status');

            //brand
            Route::post('brands/bulk-delete', [BrandController::class, 'bulkDelete'])->name('brands.bulk-delete');
            Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
            Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
            Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
            Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
            Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
            Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');

            //produce
            Route::post('produces/bulk-delete', [ProduceController::class, 'bulkDelete'])->name('produces.bulk-delete');
            Route::get('produces', [ProduceController::class, 'index'])->name('produces.index');
            Route::get('produces/create', [ProduceController::class, 'create'])->name('produces.create');
            Route::post('produces', [ProduceController::class, 'store'])->name('produces.store');
            Route::get('produces/{produce}/edit', [ProduceController::class, 'edit'])->name('produces.edit');
            Route::delete('produces/{produce}', [ProduceController::class, 'destroy'])->name('produces.destroy');
            Route::put('produces/{produce}', [ProduceController::class, 'update'])->name('produces.update');

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

            // Products
            Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::post('/products/{product}/storeOrder', [ProductController::class, 'storeOrder'])->name('products.storeOrder');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::get('/products/{parent}/editOrder/{product}', [ProductController::class, 'editOrder'])->name('products.editOrder');
            Route::put('/products/editOrder/{product}', [ProductController::class, 'updateOrder'])->name('products.updateOrder');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::post('/products/{product}/status', [ProductController::class, 'changeStatus'])->name('products.change.status');
            Route::post('/products/bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulk.status');

        });
});
