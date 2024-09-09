<?php

namespace App\Http\Requests\OrdinaryUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class changeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user && $user->hasRole('user')){
            return true ;
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
            'status'=>'required|in:completed,cancelled'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message'=>'Failed Verification please confirm the input',
                'error'=>$validator->errors()
            ]),
        );
    }
    public function attributes()
    {
        return [
            'status'=>'Status Task'
        ];
    }
    public function messages()
    {
        return [
            'required'=>'The :attribute field is required',
            'in' => 'The :attribute must be one of the following: completed, or cancelled.'
        ];

    }
}
