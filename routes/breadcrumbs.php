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

// Home > Tasks > [admin] > Clone
Breadcrumbs::for('admin.tasks.clone', function (BreadcrumbsGenerator $trail, \App\Tasks $task) {
    $trail->parent('admin.tasks.index');
    $trail->push($task->name, '#');
    $trail->push(__('Clone'), route('admin.tasks.clone', $task));
});

// Home > Tasks > [admin] > Double Check
Breadcrumbs::for('admin.dbcheck_tasks.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Danh sách DBC'), route('admin.dbcheck_tasks.index'), ['icon' => 'fal fa-check-double']);
});

// Home > DBCTasks > [admin] > Edit
Breadcrumbs::for('admin.dbcheck_tasks.edit', function (BreadcrumbsGenerator $trail, \App\Tasks $task) {
    $trail->parent('admin.dbcheck_tasks.index');
    $trail->push($task->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.dbcheck_tasks.edit', $task));
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

// Home > Customer Tasks
Breadcrumbs::for('admin.customer_tasks.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Case status'), route('admin.customer_tasks.index'), ['icon' => 'fal fa-briefcase']);
});

// Home > Tasks > [admin] > Edit
Breadcrumbs::for('admin.customer_tasks.edit', function (BreadcrumbsGenerator $trail, \App\Tasks $task) {
    $trail->parent('admin.customer_tasks.index');
    $trail->push($task->case, '#');
    $trail->push(__('Edit'), route('admin.customer_tasks.edit', $task));
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

// Home > Customer
Breadcrumbs::for('admin.customers.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('Khách hàng'), route('admin.customers.index'), ['icon' => 'fal fa-project-diagram']);
});

// Home > Customer > Create

Breadcrumbs::for('admin.customers.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.customers.index');
    $trail->push(__('Tạo'), route('admin.customers.create'));
});

// Home > Customer > [role] > Edit
Breadcrumbs::for('admin.customers.edit', function (BreadcrumbsGenerator $trail, \App\Customers $customer) {
    $trail->parent('admin.customers.index');
    $trail->push($customer->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.customers.edit', $customer));
});

// Home > AX
Breadcrumbs::for('admin.ax.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboards');
    $trail->push(__('AX'), route('admin.ax.index'), ['icon' => 'fal fa-project-diagram']);
});

// Home > AX > Create

Breadcrumbs::for('admin.ax.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.ax.index');
    $trail->push(__('Tạo'), route('admin.ax.create'));
});

// Home > AX > [role] > Edit
Breadcrumbs::for('admin.ax.edit', function (BreadcrumbsGenerator $trail, \App\AX $ax) {
    $trail->parent('admin.ax.index');
    $trail->push($ax->name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.ax.edit', $ax));
});
