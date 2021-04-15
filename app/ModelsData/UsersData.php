<?php

namespace App\ModelsData;

use App\Models\Common;
use App\User;

/**
 * App\ModelsData\UsersData
 *
 * @property int $id
 * @property string|null $name 用户名
 * @property string|null $phone 手机号
 * @property string|null $email 邮箱
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password 密码
 * @property int $status 用户状态，1有效，0无效
 * @property int $type 用户类型,权限等级
 * @property int $create_user_id 创建人
 * @property string|null $remark 备注
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $class_data_id 学校ID
 * @property int $city_id 市ID
 * @property int $province_id 省ID
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereProvinceId($value)
 * @property int $area_id 县级用户ID
 * @property int $power_type 继承type
 * @property int $power_user_id 继承用户id
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePowerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePowerUserId($value)
 * @property int $is_del
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereIsDel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 */
class UsersData extends User
{
    public $table = 'users';
    public $phone;
    public $name;
    public $status;
    public $start_time;
    public $end_time;
    public $limit = 20;
    public $page = 1;
    public $type = 1;
    public $is_super = 1; // 是否是管理员用户数据

    public function rowCount()
    {
        return $this->baseQuery()->count();
    }

    public function rowsData()
    {
        $offset = $this->page <= 1 ? 0 : ($this->page - 1) * $this->limit;
        $res = $this->baseQuery()
            ->limit($this->limit)
            ->offset($offset)
            ->get();

        foreach ($res as &$re) {
            $user = User::whereId($re['create_user_id'])->first();
            $re['create_user_name'] = $user['name'] ?? '-';
            $re['remark'] = $re['remark'] ?? '-';

            switch ($re['type']) {
                case Common::TYPE_CITY:
                    $powerData = User::whereId($re['province_id'])->first();
                    if ($powerData) {
                        $re['power_name'] = $powerData['name'];
                    }
                    break;

                case Common::TYPE_AREA:
                    $powerData = User::whereId($re['city_id'])->first();
                    if ($powerData) {
                        $re['power_name'] = $powerData['name'];
                    }
                    break;

                case Common::TYPE_SCH:
                    $powerData = User::whereId($re['area_id'])->first();
                    if ($powerData) {
                        $re['power_name'] = $powerData['name'];
                    }
                    break;

                default:
                    $re['power_name'] = '-';
                    break;
            }

            $re['type'] = Common::typeArr(1)[$re['type']] ?? $re['type'];
            $re['roles'] = [
                '超级管理员', '程序员'
            ];

            unset($re['create_user_id']);
            unset($re['power_user_id']);
            unset($re['power_type']);
            unset($re['area_id']);
            unset($re['province_id']);
            unset($re['city_id']);
            unset($re['class_data_id']);
        }

        return $res;
    }

    public function baseQuery()
    {
        $query = User::orderByDesc('id');
        $this->phone = trim($this->phone);

        if ($this->phone) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }

        $this->name = trim($this->name);

        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        if ($this->status === '0' || $this->status === '1') {
            $query->where([
                'status' => $this->status
            ]);
        }

        if ($this->is_super) {
            $query->where('type', '>', Common::TYPE_ZONE);
        } else {
            $query->where([
                'type' => Common::TYPE_ZONE
            ]);
        }

        if ($this->start_time) {
            $query->where('created_at', '>' ,$this->start_time);
        }

        if ($this->end_time) {
            $query->where('created_at', '<', $this->end_time);
        }

        return $query;
    }

    public static function lists()
    {

    }
}
