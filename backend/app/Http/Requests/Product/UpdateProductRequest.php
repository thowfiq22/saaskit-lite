<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Concerns\NormalizesInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateProductRequest extends FormRequest
{
    use NormalizesInput;

    /**
     * @var list<string>
     */
    private array $updatableFields = [
        'name',
        'description',
        'sku',
        'price',
        'currency',
        'stock',
        'is_active',
    ];

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
        $product = $this->route('product');

        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'sku' => [
                'sometimes',
                'string',
                'max:80',
                Rule::unique('products', 'sku')->ignore($product),
            ],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'nullable', 'string', 'alpha', 'size:3'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $submittedFields = array_intersect(
                array_keys($this->all()),
                $this->updatableFields
            );

            if ($submittedFields === []) {
                $validator->errors()->add(
                    'payload',
                    'At least one updatable field must be provided.'
                );
            }
        });
    }
}
