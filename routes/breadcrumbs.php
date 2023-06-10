<?php

declare(strict_types=1);

use App\Domain\Admin\Models\Admin;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Domain\Acl\Models\Role;
use App\Tasks;

// Home
Breadcrumbs::for('admin.dashboards', function (BreadcrumbsGenerator $trail) {
    $trail->push(__('Dashboards'), route('admin.dashboards'), ['icon' => 'fal fa-home']);
});

Breadcrumbs::for('admin.popup.save', function (BreadcrumbsGenerator $trail, $id) {
    $trail->push(__('Save Changes'), route('admin.popup.save', $id));
});
// Home > \App\Designs > Create

Breadcrumbs::for('admin.dashboards.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Tạo'), route('admin.dashboards.create'));
});

// Home > \App\Designs > [admin] > Edit
Breadcrumbs::for('admin.dashboards.edit', function (BreadcrumbsGenerator $trail, \App\Designs $design) {
    $trail->parent('admin.dashboards');
    $trail->push($design->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.dashboards.edit', $design));
});

/*
|--------------------------------------------------------------------------
| Application Breadcrumbs
|--------------------------------------------------------------------------
*/

// Home > Tasks
Breadcrumbs::for('admin.tasks.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
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

// Home > Pre Tasks
Breadcrumbs::for('admin.pre_tasks.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Danh sách load'), route('admin.pre_tasks.index'), ['icon' => 'fal fa-copyright']);
});

// Home > Pre Tasks > Create

Breadcrumbs::for('admin.pre_tasks.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.pre_tasks.index');
    $trail->push(__('Tạo'), route('admin.pre_tasks.create'));
});

// Home > Pre Tasks > [admin] > Edit
Breadcrumbs::for('admin.pre_tasks.edit', function (BreadcrumbsGenerator $trail, \App\PreTasks $preTask) {
    $trail->parent('admin.pre_tasks.index');
    $trail->push($preTask->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.pre_tasks.edit', $preTask));
});


/*
|--------------------------------------------------------------------------
| System Breadcrumbs
|--------------------------------------------------------------------------
*/

// Home > Admins
Breadcrumbs::for('admin.admins.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
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

// Home > Roles
Breadcrumbs::for('admin.roles.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Vai trò'), route('admin.roles.index'), ['icon' => 'fal fa-project-diagram']);
});

// Home > Roles > Create

Breadcrumbs::for('admin.roles.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.roles.index');
    $trail->push(__('Tạo'), route('admin.roles.create'));
});

// Home > Roles > [role] > Edit
Breadcrumbs::for('admin.roles.edit', function (BreadcrumbsGenerator $trail, Role $role) {
    $trail->parent('admin.roles.index');
    $trail->push($role->display_name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.roles.edit', $role));
});
