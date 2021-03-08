<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Grade;
use App\Models\YearClass;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolController extends ApiController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => [
                'required', Rule::unique('grades', 'name')->where(function ($query) {
                    return $query->where('class_data_id', auth()->user()->class_data_id);
                })
            ],
            'class' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        if (count($request->input('class')) != count(array_unique($request->input('class')))) {
            $data = [
                'class' => [
                    '班级名称不能重复'
                ]
            ];

            return $this->errorResponse('验证错误', $data, 422);
        }

        // 先入库 grade
        $model = new Grade();
        $model->class_data_id = auth()->user()->class_data_id;
        $model->city_id = auth()->user()->city_id;
        $model->province_id = auth()->user()->province_id;
        $model->name = trim($request->input('grade'));

        if ($model->save()) {
            $insertData = [];

            foreach ($request->input('class') as $class) {
                $class = trim($class);
                $insertData[] = [
                    'grade_id' => $model->id,
                    'name' => $class,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $res = \DB::table('year_classes')->insert($insertData);

            if ($res) {
                return $this->successResponse();
            }
        }

        return $this->errorResponse();
    }
}
