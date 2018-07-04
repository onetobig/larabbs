<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 14:54
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }
}