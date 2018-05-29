<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 11:14
 */

namespace App\Transformers;


use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }
}