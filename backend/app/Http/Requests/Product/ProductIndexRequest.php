<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Concerns\NormalizesInput;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    use NormalizesInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'search' => $this->normalizeString($this->input('search')),
            'sort_by' => $this->normalizeString($this->input('sort_by')),
            'sort_direction' => $this->normalizeString($this->input('sort_direction')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'in:name,price,stock,created_at,updated_at'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ];
    }
}
