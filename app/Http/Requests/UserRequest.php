<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\User\CreateUserData;
use App\DTO\User\UpdateUserData;
use App\Http\Requests\Traits\RequestValidationTrait;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class UserRequest extends BaseRequest
{
    use RequestValidationTrait;

    private const ROUTE_STORE = 'users.store';

    private const ROUTE_UPDATE = 'users.update';

    private const ROUTE_INDEX = 'users.index';

    private const VALID_STATUSES = ['active', 'inactive'];

    private const VALID_SORT_DIRECTIONS = ['asc', 'desc'];

    private const VALID_ORDER_FIELDS = ['id', 'name', 'username', 'created_at'];

    private const PER_PAGE_MIN = 1;

    private const PER_PAGE_MAX = 100;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $routeName = $this->route()?->getName();

        return match ($routeName) {
            self::ROUTE_STORE => $this->createRules(),
            self::ROUTE_UPDATE => $this->updateRules(),
            self::ROUTE_INDEX => $this->indexRules(),
            default => [],
        };
    }

    /**
     * Get validation rules for creating a user.
     */
    protected function createRules(): array
    {
        return $this->userRules(passwordRequirement: 'required');
    }

    /**
     * Get validation rules for updating a user.
     */
    protected function updateRules(): array
    {
        return $this->userRules(
            passwordRequirement: 'sometimes',
            userId: $this->route('user')
        );
    }

    /**
     * Common validation rules for user creation and update.
     */
    private function userRules(string $passwordRequirement, $userId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [$passwordRequirement, 'string', 'min:3'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'status' => ['sometimes', 'string', 'in:'.implode(',', self::VALID_STATUSES)],
        ];
    }

    /**
     * Get validation rules for listing users.
     */
    protected function indexRules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'orderBy' => ['sometimes', 'string', 'in:'.implode(',', self::VALID_ORDER_FIELDS)],
            'per_page' => [
                'required',
                'min:'.self::PER_PAGE_MIN,
                'max:'.self::PER_PAGE_MAX,
            ],
            'filter' => ['sometimes', 'array'],
            'filter.status' => [
                'sometimes',
                'string',
                'in:'.implode(',', self::VALID_STATUSES),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRoles();
        $this->preparePassword();
    }

    /**
     * Prepare roles by converting them to integers and filtering non-numeric values.
     */
    private function prepareRoles(): void
    {
        if ($this->has('roles') && is_array($this->get('roles'))) {
            $roles = array_filter(
                array_map('intval', (array) $this->get('roles')),
                static fn ($role) => $role > 0
            );
            $this->merge(['roles' => $roles]);
        }
    }

    /**
     * Remove password field for update if it's not provided.
     */
    private function preparePassword(): void
    {
        $routeName = $this->route()?->getName();
        if ($routeName === self::ROUTE_UPDATE && empty($this->input('password'))) {
            $this->request->remove('password');
        }
    }

    /**
     * Convert validated data to a DTO based on the route.
     *
     * @throws InvalidArgumentException
     */
    public function toDTO(): CreateUserData|UpdateUserData
    {
        $data = $this->validated();
        $routeName = $this->route()?->getName();

        return match ($routeName) {
            self::ROUTE_STORE => CreateUserData::from($data),
            self::ROUTE_UPDATE => UpdateUserData::from($data),
            default => throw new InvalidArgumentException("Invalid route '$routeName' for DTO conversion"),
        };
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'Username',
            'password' => 'Password',
            'roles' => 'Roles',
            'roles.*' => 'Role',
            'status' => 'Status',
            'search' => 'Search',
            'orderBy' => 'Sort by',
            'sortedBy' => 'Sort direction',
            'per_page' => 'Records per page',
            'filter.name' => 'Filter by name',
            'filter.username' => 'Filter by username',
            'filter.role' => 'Filter by role',
            'filter.status' => 'Filter by status',
            'force' => 'Force delete',
        ];
    }
    
    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => ':attribute is required.',
            'username.required' => ':attribute is required.',
            'username.unique' => ':attribute is already taken.',
            'password.required' => ':attribute is required.',
            'password.min' => ':attribute must be at least 3 characters long.',
            'roles.required' => ':attribute are required.',
            'roles.min' => 'At least one :attribute must be specified.',
            'roles.*.exists' => 'The specified :attribute does not exist.',
        ];
    }
}    
