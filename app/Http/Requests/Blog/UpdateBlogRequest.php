<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateBlogRequest extends FormRequest
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
    if ($this->has('image') && strpos($this->input('image'), 'data:image') === 0) {
      $this->request->remove('image');
    }

    return [
      'user_id' => '',
      'category_id' => '',
      'title' => '',
      'body' => '',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'

    ];
  }
}
