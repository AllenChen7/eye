<?php

namespace App\Models;


use Spatie\Permission\Models\Role;

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

    // 眼镜类型
    const GLA_TYPE_NORMAL = 0;
    const GLA_TYPE_LENS = 1;

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

    public static function glaType()
    {
        return [
            self::GLA_TYPE_NORMAL => '普通眼镜',
            self::GLA_TYPE_LENS => '隐形眼镜'
        ];
    }

    /**
     * 类型值
     * @return int[]
     */
    public static function typeArrKeys()
    {
        return [
            self::TYPE_ZONE,
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
     * @return string[]
     */
    public static function isArr()
    {
        return [
            self::YES => '是',
            self::NO => '否'
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

    /**
     * 权限数据
     * @return array[]
     */
    public static function permissionArr()
    {
        return [
            [
                'id' => 'drm-*',
                'name' => '权限管理',
                'child' => [
                    [
                        'id' => 'drm-roles',
                        'name' => '角色管理',
                        'child' => []
                    ],
                    [
                        'id' => 'drm-users',
                        'name' => '用户管理',
                        'child' => []
                    ],
                    [
                        'id' => 'drm-super-users',
                        'name' => '管理员管理',
                        'child' => []
                    ],
                    [
                        'id' => 'drm-Permissions',
                        'name' => '权限集管理',
                        'child' => []
                    ]
                ]
            ],
            [
                'id' => 'school-*',
                'name' => '学校管理',
                'child' => [
                    [
                        'id' => 'school-school-*',
                        'name' => '学校管理',
                        'child' => [
                            [
                                'id' => 'school-school-view',
                                'name' => '查看',
                                'child' => []
                            ],
                            [
                                'id' => 'school-school-add',
                                'name' => '新增',
                                'child' => []
                            ],
                            [
                                'id' => 'school-school-update',
                                'name' => '编辑',
                                'child' => []
                            ],
                            [
                                'id' => 'school-school-delete',
                                'name' => '删除',
                                'child' => []
                            ]
                        ]
                    ],
                    [
                        'id' => 'school-grade-*',
                        'name' => '班级管理',
                        'child' => [
                            [
                                'id' => 'school-grade-view',
                                'name' => '查看',
                                'child' => []
                            ],
                            [
                                'id' => 'school-grade-add',
                                'name' => '新增',
                                'child' => []
                            ],
                            [
                                'id' => 'school-grade-update',
                                'name' => '编辑',
                                'child' => []
                            ],
                            [
                                'id' => 'school-grade-delete',
                                'name' => '删除',
                                'child' => []
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 'student-*',
                'name' => '学生管理',
                'child' => [
                    [
                        'id' => 'student-view',
                        'name' => '查看',
                        'child' => []
                    ],
                    [
                        'id' => 'student-add',
                        'name' => '新增',
                        'child' => []
                    ],
                    [
                        'id' => 'student-update',
                        'name' => '更新',
                        'child' => []
                    ],
                    [
                        'id' => 'student-delete',
                        'name' => '删除',
                        'child' => []
                    ],
                    [
                        'id' => 'student-import',
                        'name' => '导入',
                        'child' => []
                    ],
                    [
                        'id' => 'student-export',
                        'name' => '导出',
                        'child' => []
                    ]
                ]
            ],
            [
                'id' => 'plan-*',
                'name' => '验光计划',
                'child' => [
                    [
                        'id' => 'plan-view',
                        'name' => '查看',
                        'child' => []
                    ],
                    [
                        'id' => 'plan-add',
                        'name' => '新增',
                        'child' => []
                    ],
                    [
                        'id' => 'plan-update',
                        'name' => '更新',
                        'child' => []
                    ],
                    [
                        'id' => 'plan-delete',
                        'name' => '删除',
                        'child' => []
                    ],
                ]
            ],
            [
                'id' => 'wx-*',
                'name' => '家长管理',
                'child' => [
                    [
                        'id' => 'wx-view',
                        'name' => '查看',
                        'child' => []
                    ],
                    [
                        'id' => 'wx-update',
                        'name' => '更新',
                        'child' => []
                    ],
                ]
            ],
            [
                'id' => 'big-*',
                'name' => '大数据统计',
                'child' => [
                    [
                        'id' => 'big-view',
                        'name' => '查看',
                        'child' => []
                    ]
                ]
            ],
            [
                'id' => 'setting-*',
                'name' => '设置',
                'child' => [
                    [
                        'id' => 'setting-optometry',
                        'name' => '查看',
                        'child' => []
                    ]
                ]
            ]
        ];
    }

    /**
     * 权限列表
     * @param string $user
     * @return array
     */
    public static function getPermissionList($user = '')
    {
        if (!$user) {
            $user = auth()->user();
        }

        $permissionArr = [];

        switch ($user->type) {
            case Common::TYPE_XM:
            case Common::TYPE_ZONE:
                $permission = $user->getAllPermissions();

                break;
            case Common::TYPE_AREA:
                $permission = Role::findByName('area')->getAllPermissions();
                break;
            case Common::TYPE_PROV:
                $permission = Role::findByName('province')->getAllPermissions();
                break;
            case Common::TYPE_CITY:
                $permission = Role::findByName('city')->getAllPermissions();
                break;
            default:
                $permission = [];
                break;
        }

        foreach ($permission as $p) {
            $permissionArr[] = $p['name'];
        }

        return $permissionArr;
    }

    public static function transPhoto($sex = 1)
    {
        if ($sex) {
            return 'http://todo.hyyphp.online/uploads/images/avatars/202012/04/14_1607087777_12WaTMK3AT.jpeg';
        } else {
            return 'http://todo.hyyphp.online/uploads/images/avatars/202012/04/14_1607087777_12WaTMK3AT.jpeg';
        }
    }
}
