<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'vendor_id' => ['sometimes', 'integer', 'exists:vendors,id'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:pending,sent,paid,overdue'],
        ];
    }
}
