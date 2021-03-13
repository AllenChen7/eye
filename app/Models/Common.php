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

    // 学生状态
    const STU_STATUS_ACTIVE = 1;
    const STU_STATUS_STOP = 2;
    const STU_STATUS_DEL = 0;

    // 计划状态
    const PLAN_STATUS_ACTIVE = 1;
    const PLAN_STATUS_DONE = 2;
    const PLAN_STATUS_PASS = 0; // 超时了

    /**
     * 层级类型
     * @param int $type
     * @return string[]
     */
    public static function typeArr($type = 0)
    {
        $arr = [
            self::TYPE_PROV => '省级',
            self::TYPE_CITY => '市级',
            self::TYPE_AREA => '县级',
            self::TYPE_SCH  => '校级'
        ];

        if ($type === 1) {
            $arr = [
                self::TYPE_XM => '希铭',
                self::TYPE_PROV => '省级',
                self::TYPE_CITY => '市级',
                self::TYPE_AREA => '县级',
                self::TYPE_SCH  => '校级'
            ];
        }

        return $arr;
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

    public static function sexArr()
    {
        return [
            self::SEX_MEN => '男',
            self::SEX_WOMEN => '女',
            self::SEX_UNKNOWN => '未知'
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

    /**
     * 学生状态
     * @return string[]
     */
    public static function studentStatusArr()
    {
        return [
            self::STU_STATUS_DEL => '删除',
            self::STU_STATUS_ACTIVE => '正常',
            self::STU_STATUS_STOP => '休学中'
        ];
    }

    /**
     * 验光计划状态
     * @return string[]
     */
    public static function planStatusArr()
    {
        return [
            self::PLAN_STATUS_ACTIVE => '待执行',
            self::PLAN_STATUS_DONE => '已完成'
        ];
    }

    public static function transYearOld($birthday)
    {
        $arr = explode('-', $birthday);
        $year = $arr[0];
        $nowYear = date('Y');

        return $nowYear - $year + 1; // todo
    }
}
