<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Plan;
use App\Models\YearClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends ApiController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|max:100',
            'plan_data' => 'required',
            'plan_user' => 'nullable|max:64',
            'remark' => 'nullable|max:200',
            'grade_id'  => 'required|exists:grades,id',
            'class_id' => 'required|exists:year_classes,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $model = new Plan();
        $model->name = $request->input('name');
        $model->plan_date = $request->input('plan_data');
        $model->plan_user = $request->input('plan_user');
        $model->remark = $request->input('remark');
        $model->grade_id = $request->input('grade_id');
        $model->year_class_id = $request->input('class_id');
        $model->create_user_id = auth()->id();

        $classInfo = YearClass::where([
            'id' => $request->input('class_id')
        ])->first();

        if ($classInfo['grade_id'] != $request->input('grade_id')) {
            return $this->errorResponse('验证错误', [
                'grade_id' => [
                    '班级不属于该年级'
                ]
            ], 422);
        }

        $model->class_data_id = $classInfo['class_data_id'];

        if ($model->save()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
