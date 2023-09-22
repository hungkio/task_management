<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AXRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->method() == "POST") {
            return [
                'name' => ['unique:ax','required', 'string', 'max:255'],
                'priority' => ['required','numeric'],
                'estimate_editor' => ['required', 'numeric'],
                'estimate_QA' => ['required', 'numeric'],
                'real_amount' => ['numeric'],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'priority' => ['required','numeric'],
                'estimate_editor' => ['required', 'numeric'],
                'estimate_QA' => ['required', 'numeric'],
                'real_amount' => ['numeric'],
            ];
        }

    }

    public function attributes()
    {
        return [
            'name' => 'Tên AX',
            'real_amount' => 'Tỷ lệ done input'
        ];
    }
}
