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

/**
 * 无断点输出
 * @param mixed ...$args
 */
function echo_r(...$args)
{
    foreach($args as &$x){
        if (method_exists($x, 'toArray')) {
            $x = $x->toArray();
        }
    }

    echo '<pre>';
    var_dump(...$args);
}

/**
 * @param $date
 * @param string $format
 * @return bool
 */
function isDate($date, $format = 'Y-m-d')
{
    if (date($format, strtotime($date)) === $date) {
        return true;
    } else {
        return false;
    }
}
