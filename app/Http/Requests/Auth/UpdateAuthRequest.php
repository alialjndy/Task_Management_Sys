<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateAuthRequest extends FormRequest
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
     * Summary of rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'=>'nullable|unique:users,name',
            'email'=>'nullable|email',
            'password'=>'nullable',
            'role'=>'nullable|in:manager,user'
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
     * Summary of attributes
     * @return string[]
     */
    public function attributes()
    {
        return [
            'email'=>'User Email',
            'password'=>'User Password',
            'role'=>'User Role'
        ];
    }
    /**
     * Summary of messages
     * @return string[]
     */
    public function messages()
    {
        return [
            'email'=>'please Input a valid Email.',
            'role.in'=>'Role value must be in manager or user.'
        ];
    }
}
