<?php

namespace App\Http\Requests\Backend;

use App\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $id = ($this->category->id) ? $this->category->id : null;

        return [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $id,
            'description' => 'nullable',
            'type' => 'in:' . CategoryType::toString(),
        ];
    }
}
