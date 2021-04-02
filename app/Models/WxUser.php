<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\WxUser
 *
 * @property int $id
 * @property string $openid openid
 * @property string|null $union_id union_id
 * @property string|null $session_key session key
 * @property string|null $nickname
 * @property int $gender 性别，1男2女
 * @property string|null $avatar 头像
 * @property string|null $province 省
 * @property string|null $country 国家
 * @property string|null $city 市
 * @property string|null $language 语言
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereUnionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $password password
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser wherePassword($value)
 * @property int $class_data_id 学校ID
 * @property string $nums 查询次数
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereNums($value)
 */
class WxUser extends Authenticatable implements JWTSubject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'openid', 'union_id', 'session_key', 'nickname', 'gender', 'avatar', 'province', 'country', 'city',
        'language', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'status', 'session_key', 'updated_at', 'union_id'
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
