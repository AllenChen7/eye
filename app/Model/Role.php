<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property int $create_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $last_user_id 最后操作人
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereLastUserId($value)
 * @property int $is_del 是否删除
 * @property int $status 状态
 * @property-read mixed $role_name
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereIsDel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereStatus($value)
 */
class Role extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'guard_name', 'create_user_id', 'is_del'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getNameAttribute($value)
    {
        $arr = explode('_', $value);

        return $arr[1] ?? $value;
    }

    public function getRoleNameAttribute($value)
    {
        $arr = explode('.', $value);

        return $arr[1] ?? $value;
    }
}
