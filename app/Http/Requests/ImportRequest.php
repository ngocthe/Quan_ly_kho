<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'etd' => 'required',
            'eta' => 'required',
            'term' => 'required',
            'source_id' => 'required',
            'supplier_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|min:1',
            'unit' => 'required',
            'price' => 'required',
            'payment_term_id' => 'required',
        ];
    }
}
