<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 15:08
 */

namespace App\Transformers;


use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
        ];
    }
}