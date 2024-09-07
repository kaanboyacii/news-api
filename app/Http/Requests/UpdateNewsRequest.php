<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Başlık gereklidir.',
            'content.required' => 'İçerik gereklidir.',
            'image.image' => 'Görsel formatı geçerli olmalıdır.',
            'image.mimes' => 'Görsel formatı sadece webp olabilir.',
            'image.max' => 'Görsel boyutu 2MB\'dan büyük olmamalıdır.',
        ];
    }
}