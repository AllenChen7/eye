<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\YearClass
 *
 * @property int $id
 * @property int $grade_id 年级ID
 * @property string $name 名称
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass query()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class YearClass extends Model
{
    protected $hidden = [
        'created_at', 'updated_at', 'status', 'grade_id'
    ];
}
