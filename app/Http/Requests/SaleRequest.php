<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'plot_id'=>'required|min:1',
            'fname'=>'required',
            'lname'=>'required',
            'gender_id'=>'required|min:1',
            'phone'=>'required',
            'payment_period_id'=>'required|min:1',
            'constant_id'=>'required|min:1',
            'created_at'=>'required'
            ];
    }
}
