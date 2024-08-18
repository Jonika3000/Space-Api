<?php

namespace App\Http\Requests;

use App\Enums\BodiesTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBodyRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'type' => [Rule::enum(BodiesTypeEnum::class), 'required'],
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'galaxy_id' => 'required|integer'
        ];
    }
}
