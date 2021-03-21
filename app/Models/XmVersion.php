<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\XmVersion
 *
 * @property int $id
 * @property int $v_code 版本号
 * @property string $url 下载地址
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XmVersion whereVCode($value)
 * @mixin \Eloquent
 */
class XmVersion extends Model
{
    //
}
