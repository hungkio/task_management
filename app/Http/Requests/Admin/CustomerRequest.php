<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->method() == "POST") {
            return [
                'name' => ['unique:customers','required', 'string', 'max:255'],
                'ax' => ['required', 'string', 'max:255'],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'ax' => ['required', 'string', 'max:255'],
            ];
        }

    }

    public function attributes()
    {
        return [
            'name' => 'Tên customer',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Không cho phép nhập ký tự đặc biệt',
        ];
    }
}
