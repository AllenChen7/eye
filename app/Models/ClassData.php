<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClassData
 *
 * @property int $id
 * @property int $province_id 省ID
 * @property int $city_id 市ID
 * @property int $create_user_id 创建人
 * @property string $name 名称
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $area_id 县级用户ID
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereAreaId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Grade[] $grade
 * @property-read int|null $grade_count
 */
class ClassData extends Model
{
    protected $fillable = [
        'province_id', 'city_id', 'create_user_id', 'name', 'status', 'area_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    public function grade()
    {
        return $this->hasMany('App\Models\Grade');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * 获取当前用户可以查看的班级id
     * @return mixed
     */
    public function idArr()
    {
        $user = auth()->user();
        $type = $user['type'];

        if (!$type) {
            $type = $user['power_type'];
        }

        $query = ClassData::orderByDesc('id');

        switch ($type) {
            case Common::TYPE_CITY:
                $query->where([
                    'city_id' => $user['city_id']
                ]);
                break;
            case Common::TYPE_AREA:
                $query->where([
                    'area_id' => $user['area_id']
                ]);
                break;
            case Common::TYPE_PROV:
                $query->where([
                    'province_id' => $user['province']
                ]);
                break;
            case Common::TYPE_XM:
                break;
            case Common::TYPE_SCH:
                $query->where([
                    'id' => $user['class_data_id']
                ]);
                break;
            default:
                $query->where([
                    'id' => 0
                ]);
                break;
        }

        return $query->select(['id'])->get()->pluck();
    }
}
