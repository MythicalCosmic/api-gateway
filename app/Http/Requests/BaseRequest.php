<?php

namespace App\Http\Requests;

use App\Exceptions\ForbiddenException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Contracts\ValidationContract;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest implements ValidationContract
{
    abstract public function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    public function getValidationRules(): array
    {
        return $this->rules();
    }

    /**
     * Validation error messages.
     */
    public function messages(): array
    {
        return array_merge($this->defaultMessages(), $this->customMessages());
    }

    /**
     * Default validation messages.
     */
    protected function defaultMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute field must be a string.',
            'integer' => 'The :attribute field must be an integer.',
            'numeric' => 'The :attribute field must be a number.',
            'array' => 'The :attribute field must be an array.',
            'email' => 'The :attribute field must be a valid email address.',
            'unique' => 'The :attribute field value already exists.',
            'exists' => 'The selected :attribute value is invalid.',
            'max' => [
                'numeric' => 'The :attribute field may not be greater than :max.',
                'string' => 'The :attribute field may not be greater than :max characters.',
                'array' => 'The :attribute field may not contain more than :max items.',
            ],
            'min' => [
                'numeric' => 'The :attribute field must be at least :min.',
                'string' => 'The :attribute field must be at least :min characters.',
                'array' => 'The :attribute field must contain at least :min items.',
            ],
            'in' => 'The selected :attribute value is invalid.',
            'date' => 'The :attribute field is not a valid date.',
            'date_format' => 'The :attribute field does not match the format :format.',
            'boolean' => 'The :attribute field must be a boolean.',
            'confirmed' => 'The :attribute field confirmation does not match.',
            'size' => [
                'numeric' => 'The :attribute field must be :size.',
                'file' => 'The file size in the :attribute field must be :size KB.',
                'string' => 'The :attribute field must be :size characters.',
                'array' => 'The :attribute field must contain :size items.',
            ],
        ];
    }


    /**
     * Custom validation messages.
     */
    protected function customMessages(): array
    {
        return [];
    }

    /**
     * Get filter parameters from the request.
     */
    protected function getFilterParameters(): array
    {
        return array_filter($this->only([
            'search',
            'orderBy',
            'sortedBy',
            'per_page',
            'filter',
        ]));
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->mergeSearch();
        $this->mergePerPage();
        $this->mergeOrderBy();
    }

    protected function mergeSearch(): void
    {
        if ($this->has('search')) {
            $this->merge(['search' => strip_tags($this->get('search'))]);
        }
    }

    protected function mergePerPage(): void
    {
        if ($this->has('per_page')) {
            $this->merge(['per_page' => (int) $this->get('per_page')]);
        }
    }

    protected function mergeOrderBy(): void
    {
        if ($this->has('orderBy')) {
            $this->merge([
                'orderBy' => strtolower($this->get('orderBy')),
                'sortedBy' => strtolower($this->get('sortedBy', 'asc')),
            ]);
        }
    }

    public function sanitizeInput(array $input): array
    {
        return array_intersect_key($input, array_flip(array_keys($this->rules())));
    }

    public function validateAndTransform(array $data): array
    {
        return $this->validate($data);
    }

    protected function failedValidation(Validator $validator): void
    {

        throw new HttpResponseException(
            (new ValidationException($validator->errors()->toArray()))->render()
        );
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            (new ForbiddenException)->render()
        );
    }

    protected function formatErrors(array $errors): array
    {
        return array_map(static function ($messages, $field) {
            return [
                'field' => $field,
                'messages' => $messages,
                'first_message' => $messages[0] ?? null,
            ];
        }, $errors, array_keys($errors));
    }
}
