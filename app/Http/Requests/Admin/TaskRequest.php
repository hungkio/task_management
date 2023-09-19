<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->method() == "POST") {
            return [
                'name' => ['unique:tasks','required', 'string', 'max:255'],
                'path' => ['required', 'string', 'max:255'],
                'case' => ['required','string', 'max:255'],
                'customer' => ['required', 'string', 'max:255'],
                'countRecord' => ['required', 'numeric'],
                'level' => ['required','string', 'max:255'],
                'deadline' => [function ($attribute, $value, $fail) {
                    if ($value && !Carbon::createFromFormat('Y-m-d H:i', $value)) {
                        return false;
                    }
                }]
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'path' => ['required', 'string', 'max:255'],
                'case' => ['required','string', 'max:255'],
                'customer' => ['required', 'string', 'max:255'],
                'countRecord' => ['required', 'numeric'],
                'level' => ['required','string', 'max:255'],
                'deadline' => [function ($attribute, $value, $fail) {
                    if ($value && !Carbon::createFromFormat('Y-m-d H:i', $value)) {
                        return false;
                    }
                }]
            ];
        }

    }

    public function attributes()
    {
        return [
            'name' => 'Tên case',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Không cho phép nhập ký tự đặc biệt',
        ];
    }
}
