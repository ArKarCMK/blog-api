<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
    if ($this->has('image') && $this->input('image') === 'null') {
      $this->merge(['image' => null]);
    }
    return [
      'user_id' => '',
      'category_id' => 'required',
      'title' =>  'required|string',
      'body' => 'required|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ];
  }
}
