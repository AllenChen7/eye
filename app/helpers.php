<?php

/**
 * 数组型输出 dd
 * @param $model
 */
function dda($model)
{
    if (method_exists($model, 'toArray')) {
        dd($model->toArray());
    } else {
        dd($model);
    }
}

/**
 * 数组型输出 dd x
 * @param mixed ...$args
 */
function ddax(...$args)
{
    foreach($args as &$x){
        if (method_exists($x, 'toArray')) {
            $x = $x->toArray();
        }
    }
    dd(...$args);
}
