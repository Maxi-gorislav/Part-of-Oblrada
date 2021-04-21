<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class RoleRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance() {
        // Adding type before validation
        $this->request->add(['guard_name' => 'admin']);
        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'permissions' => 'required',
        ];
    }
}
