<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AModuleStepUpdateRequest extends FormRequest
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
            'title' => 'required|min:2|max:100',
			'db_table' => 'required|min:2|max:100',
			'order_by_column' => 'required',
			'main_column' => 'required',
            'model_name' => 'required',
        ];
    }
}
