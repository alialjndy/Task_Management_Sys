<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=>['required','email'],
            'password'=>['required']
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'=>'فشل',
                'message'=>'خطأ : عدم تطابق في البيانات المرسلة',
                'error'=>$validator->errors()
            ]));
    }
    public function attributes(){
        return [
            'email'=>'البريد الإلكتروني',
            'password'=>'كلمة السر'
        ];
    }
    public function messages()
    {
        return [
            'required'=>'حقل :attribute هو حقل أجباري',
            'email'=>'خطأ : يرجى إدخال بريد ألكتروني صالح'
        ];
    }
}
