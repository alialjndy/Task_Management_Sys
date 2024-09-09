<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user && $user->hasRole('manager')){
            return true;
        }else{
            abort(401, 'Unauthorized');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'=>'required|exists:users,id'
        ];
    }
    /**
     * Summary of failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'=>'failed',
            'message'=>'Failed Verification please confirm the input',
            'error'=>$validator->errors()
        ]));
    }
    /**
     *
     * @return string[]
     */
    public function attributes()
    {
        return [
            'user_id'=>'User ID'
        ];
    }
    /**
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'required'=>'The :attribute field is required',
            'exists'=>'The :attribute value must exists in table users'
        ];
    }
}
