<?php

declare(strict_types=1);

use App\Domain\Admin\Models\Admin;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('admin.designs.index', function (BreadcrumbsGenerator $trail) {
    $trail->push(__('Trang chủ'), route('admin.designs.index'), ['icon' => 'fal fa-home']);
});

// Home > \App\Designs > Create

Breadcrumbs::for('admin.designs.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.designs.index');
    $trail->push(__('Tạo'), route('admin.designs.create'));
});

// Home > \App\Designs > [admin] > Edit
Breadcrumbs::for('admin.designs.edit', function (BreadcrumbsGenerator $trail, \App\Designs $design) {
    $trail->parent('admin.designs.index');
    $trail->push($design->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.designs.edit', $design));
});

/*
|--------------------------------------------------------------------------
| Application Breadcrumbs
|--------------------------------------------------------------------------
*/

// Home > Tasks
Breadcrumbs::for('admin.tasks.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.designs.index');
    $trail->push(__('Danh sách task'), route('admin.tasks.index'), ['icon' => 'fal fa-copyright']);
});

// Home > Tasks > Create

Breadcrumbs::for('admin.tasks.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.tasks.index');
    $trail->push(__('Tạo'), route('admin.tasks.create'));
});

// Home > Tasks > [admin] > Edit
Breadcrumbs::for('admin.tasks.edit', function (BreadcrumbsGenerator $trail, \App\Tasks $task) {
    $trail->parent('admin.tasks.index');
    $trail->push($task->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.tasks.edit', $task));
});


/*
|--------------------------------------------------------------------------
| System Breadcrumbs
|--------------------------------------------------------------------------
*/

// Home > Admins
Breadcrumbs::for('admin.admins.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.designs.index');
    $trail->push(__('Tài khoản'), route('admin.admins.index'), ['icon' => 'fal fa-user']);
});

// Home > Admins > Create

Breadcrumbs::for('admin.admins.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.admins.index');
    $trail->push(__('Tạo'), route('admin.admins.create'));
});

// Home > Admins > [admin] > Edit
Breadcrumbs::for('admin.admins.edit', function (BreadcrumbsGenerator $trail, Admin $admin) {
    $trail->parent('admin.admins.index');
    $trail->push($admin->email, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.admins.edit', $admin));
});
