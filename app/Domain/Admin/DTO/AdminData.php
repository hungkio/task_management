<?php

declare(strict_types=1);

namespace App\Domain\Admin\DTO;

use App\Http\Requests\Admin\AdminRequest;
use Spatie\DataTransferObject\DataTransferObject;

class AdminData extends DataTransferObject
{
    public string $first_name;

    public string $last_name;

    public string $email;

    public ?string $password;

    public string $roles;
    public string $level;
    public string $customer_for_work;
    public int $is_ctv;
    public string $lock_task;
    public string $customer;

    public static function fromRequest(AdminRequest $request): AdminData
    {
        return new self([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'roles' => $request->input('roles'),
            'level' => implode(',', $request->input('level')),
            'customer_for_work' => implode(',', $request->input('customer_for_work')),
            'is_ctv' => ($request->input('is_ctv') == 'on') ? 1 : 0,
            'lock_task' => $request->input('lock_task'),
            'customer' => $request->input('customer') ?? '',
        ]);
    }
}
