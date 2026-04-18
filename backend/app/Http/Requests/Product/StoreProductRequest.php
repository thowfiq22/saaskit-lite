<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Concerns\NormalizesInput;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use NormalizesInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $payload = [
            'name' => $this->normalizeString($this->input('name')),
            'description' => $this->normalizeString($this->input('description')),
            'sku' => $this->normalizeString($this->input('sku')),
        ];

        if ($this->filled('currency')) {
            $payload['currency'] = strtoupper((string) $this->string('currency'));
        }

        $this->merge(array_filter($payload, static fn (mixed $value): bool => $value !== null));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'alpha', 'size:3'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
