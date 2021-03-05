<?php

namespace App\ModelsData;

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

    const TYPE_XM = 1;
    const TYPE_PROV = 2;
    const TYPE_CITY = 3;
    const TYPE_SCH = 4;

    public static function typeArr()
    {
        return [
            self::TYPE_XM => '希铭公司',
            self::TYPE_PROV => '省级管理员',
            self::TYPE_CITY => '县市级管理员',
            self::TYPE_SCH => '学校级管理员'
        ];
    }

    public function rowCount()
    {
        $rows = $this->baseQuery()->get()->groupBy('type');
        $data = [];

        foreach (self::typeArr() as $key => $type) {
            $data[] = [
                'name' => $type,
                'value' => $key,
                'is_default' => $this->type == $key ? 1 : 0,
                'count' => isset($rows[$key]) ? count($rows[$key]) : 0
            ];
        }

        return $data;
    }

    public function rowsData()
    {
        $offset = $this->page <= 1 ? 0 : ($this->page - 1) * $this->limit;

        return $this->baseQuery()
            ->limit($this->limit)
            ->offset($offset)
            ->where([
                'type' => $this->type
            ])->get();
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

        if ($this->status === 0 || $this->status === 1) {
            $query->where([
                'status' => $this->status
            ]);
        }

        if ($this->start_time) {
            $query->where([
                'created_at', '>' ,$this->start_time
            ]);
        }

        if ($this->end_time) {
            $query->where([
                'created_at', '<', $this->end_time
            ]);
        }

        return $query;
    }
}
