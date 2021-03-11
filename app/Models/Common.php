<?php

namespace App\Models;


class Common
{
    // 类型
    const TYPE_ZONE = 0;
    const TYPE_XM = 10;
    const TYPE_PROV = 20;
    const TYPE_CITY = 30;
    const TYPE_AREA = 40;
    const TYPE_SCH = 50;

    // 状态
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    // 性别
    const SEX_MEN = 1;
    const SEX_WOMEN = 2;
    const SEX_UNKNOWN = 0;

    // 是否
    const YES = 1;
    const NO = 0;

    /**
     * 层级类型
     * @return string[]
     */
    public static function typeArr()
    {
        return [
            self::TYPE_PROV => '省级',
            self::TYPE_CITY => '市级',
            self::TYPE_AREA => '县级',
            self::TYPE_SCH  => '校级'
        ];
    }

    /**
     * 类型值
     * @return int[]
     */
    public static function typeArrKeys()
    {
        return [
            self::TYPE_XM,
            self::TYPE_PROV,
            self::TYPE_CITY,
            self::TYPE_AREA,
            self::TYPE_SCH,
        ];
    }

    /**
     * @return int[]
     */
    public static function sexArrKeys()
    {
        return [
            self::SEX_UNKNOWN,
            self::SEX_MEN,
            self::SEX_WOMEN
        ];
    }

    /**
     * @return int[]
     */
    public static function isKeys()
    {
        return [
            self::YES,
            self::NO
        ];
    }

    /**
     * 状态
     * @return string[]
     */
    public static function statusArr()
    {
        return [
            self::STATUS_ACTIVE     => '启用',
            self::STATUS_DISABLED   => '禁用'
        ];
    }
}
