<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->method() == "POST") {
            return [
                'name' => ['unique:tasks','required', 'string', 'max:255', 'regex:/^[0-9 a-zA-Z_ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼẾỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệếỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ,;.]+$/'],
                'path' => ['string', 'max:255'],
                'date' => ['string', 'max:255'],
                'month' => ['string', 'max:255'],
                'case' => ['string', 'max:255'],
                'customer' => ['string', 'max:255'],
                'countRecord' => ['numeric'],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255', 'regex:/^[0-9 a-zA-Z_ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼẾỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệếỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ,;.]+$/'],
                'path' => ['string', 'max:255'],
                'date' => ['string', 'max:255'],
                'month' => ['string', 'max:255'],
                'case' => ['string', 'max:255'],
                'customer' => ['string', 'max:255'],
                'countRecord' => ['numeric'],
            ];
        }

    }

    public function attributes()
    {
        return [
            'name' => 'Tên xưởng',
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
