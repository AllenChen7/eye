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

function excelTime($date, $time = false) {
    if(function_exists('gregoriantojd')){
        if (is_numeric( $date )) {
            $jd = gregoriantojd( 1, 1, 1970 );
            $gregorian = jdtogregorian( $jd + intval ( $date ) - 25569 );
            $date = explode( '/', $gregorian );
            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    }else{
        $date=$date>25568?$date+1:25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs=(70 * 365 + 17+2) * 86400;
        $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
    }
    return $date;
}

function transDate($t0) {
    $t1 = intval(($t0 - 25569) * 3600 * 24); //转换成1970年以来的秒数

    return gmdate('Y-m-d',$t1);//格式化时间
}
