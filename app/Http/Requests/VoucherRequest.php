<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'type' => 'required|integer',
            'value' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên voucher không được để trống',
            'dateStart.required' => 'Vui lòng nhập ngày bắt đầu',
            'dateStart.date' => 'Ngày bắt đầu không hợp lệ',
            'dateEnd.required' => 'Vui lòng nhập ngày kết thúc',
            'dateEnd.date' => 'Ngày kết thúc không hợp lệ',
            'dateEnd.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'type.required' => 'Loại voucher không được để trống',
            'type.in' => 'Loại voucher phải là "percent" hoặc "fixed"',
            'value.required' => 'Giá trị voucher không được để trống',
            'value.integer' => 'Giá trị phải là số nguyên',
            'value.min' => 'Giá trị không được âm',
        ];
    }
}
