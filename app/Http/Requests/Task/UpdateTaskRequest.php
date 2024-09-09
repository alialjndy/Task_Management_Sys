<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateTaskRequest extends FormRequest
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
            'manager_id'=>'nullable|exists:users,id',
            'title'=>'nullable|string|max:100',
            'description'=>'nullable|string',
            'date_due'=>'nullable|date',
            'priority'=>'nullable|in:low,medium,high',
            'status'=>'nullalbe|in:pending,in_progress,completed,cancelled',
            'to_assigned'=>'nullable|exists:users,id'
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
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'Failed verification please confirm the input',
                'errors'=>$validator->errors()
            ],401)
        );
    }
    /**
     * Summary of attributes
     * @return string[]
     */
    public function attributes()
    {
        return [
            'title' => 'Task Title',
            'description' => 'Task Description',
            'date_due' => 'Due Date',
            'priority' => 'Task Priority',
            'status' => 'Task Status',
            'to_assigned' => 'Assigned To'
        ];
    }
    /**
     * Summary of messages
     * @return string[]
     */
    public function messages()
    {
        return [
            'string'=>'The :attribute field must be a string',
            'max'=>'The :attribute field connot be longer than 100 characters',

            'priority.in' => 'The priority must be one of the following: low, medium, or high',
            'status.in' => 'The status must be one of the following: pending, in_progress, completed, or cancelled',
            'exists'=>'The value of assigned to must be in column id '
        ];
    }
}
