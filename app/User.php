<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $phone 手机号
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @property int $status 用户状态，1有效，0无效
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @property string|null $remark 备注
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRemark($value)
 * @property int $type 用户类型,权限等级
 * @property int $create_user_id 创建人
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @property int $class_data_id 学校ID
 * @property int $city_id 市ID
 * @property int $province_id 省ID
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvinceId($value)
 * @property int $area_id 县级用户ID
 * @property int $power_type 继承type
 * @property int $power_user_id 继承用户id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePowerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePowerUserId($value)
 * @property int $is_del
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'remark', 'province_id', 'city_id', 'class_data_id', 'user_id',
        'power_user_id', 'power_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'updated_at', 'email', 'is_del'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
