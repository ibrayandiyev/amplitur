<?php

namespace App\Http\Requests\Backend;

use App\Enums\AccessStatus;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = ($this->user) ? $this->user->id : null;

        return [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $userId,
            'username' => 'required|min:6|unique:users,username,' . $userId,
            'password' => 'nullable|min:6|confirmed',
            'language' => 'required',
            'status' => 'required|in:' . AccessStatus::toString(),
        ];
    }
}
