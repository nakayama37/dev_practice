<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
        'categories' => ['required', 'array'],
        'title' => ['required', 'max:50'], 
        'content' => ['required', 'max:200'], 
        'event_date' => ['required', 'date'], 
        'start_at' => ['required'], 
        'end_at' => ['required', 'after:start_at'], 
        'max_people' => ['required', 'numeric', 'between:1,20'],
        'price' => ['numeric'],
        'is_public' => ['required', 'boolean'],
        'postcode' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
        'prefecture' => ['required', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:255'],
        'street' => ['required', 'string', 'max:255']
    ];
  }
}
