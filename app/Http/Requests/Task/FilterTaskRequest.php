<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user && ($user->hasRole('admin')||$user->hasRole('manager') )){
            return true ;
        }else{
            abort(403, 'Unauthorized action. Only admins and managers can access this resource.');
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
            'status'=>'sometimes|in:pending,in_progress,completed,cancelled',
            'priority'=>'sometimes|in:low,medium,high'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'=>'failed',
            'message'=>'Failed Verification Please confirm the input',
            'errors'=>$validator->errors()
        ]));
    }
    public function attributes()
    {
        return [
            'status'=>'Task status',
            'priority'=>'Task priority'
        ];
    }
    public function messages()
    {
        return [
            'in'=>'please input a correct value'
        ];
    }
}
