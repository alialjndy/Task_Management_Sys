<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user && $user->hasRole('admin')){
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
            'role'=>'required|string|exists:roles,name'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'Failed Verification please confirm the input',
                'error'=>$validator->errors()
            ])
        );
    }
    public function attributes()
    {
        return [
            'role'=>'User Role'
        ];
    }
    public function messages()
    {
        return [
            'required'=>'The :attribute field is required',
            'string'=>'The :attribute field must be a stirng',
            'exists'=>'The Role value must be in the column name'
        ];
    }
}
