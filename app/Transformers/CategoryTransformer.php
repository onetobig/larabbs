<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 10:58
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
            'description' =>  $item->description,
        ];
    }
}