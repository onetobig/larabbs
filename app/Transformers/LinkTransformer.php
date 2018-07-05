<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 17:07
 */

namespace App\Transformers;


use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'link' => $item->link
        ];
    }
}