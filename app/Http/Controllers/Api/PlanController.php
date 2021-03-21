<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Plan;
use App\Models\Student;
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
            // 将学生列入验光计划
            Student::where([
                'year_class_id' => $request->input('class_id')
            ])->update([
                'plan_id' => $model->id,
                'plan_status' => 1
            ]);
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function list(Request $request)
    {
        $name = $request->input('name');
        $gradeId = $request->input('grade_id');
        $classId = $request->input('class_id');
        $type = $request->input('type', 1);

        $query = Plan::where([
            'is_del' => Common::NO
        ])->orderByDesc('id');

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($gradeId) {
            $query->where([
                'grade_id' => $gradeId
            ]);
        }

        if ($classId) {
            $query->where([
                'year_class_id' => $classId
            ]);
        }

        $countRows = $query->get()->groupBy('status');
        $data = [];
        $statusArr = [
            1 => '进行中',
            2 => '已完成'
        ];

        foreach ($statusArr as $key => $value) {
            $data[] = [
                'name' => $value,
                'value' => $key,
                'is_default' => $type == $key ? 1 : 0,
                'count' => isset($countRows[$key]) ? count($countRows[$key]) : 0
            ];
        }

        $rows = $query->where([
            'status' => $type
        ])->get();

        foreach ($rows as $row) {
            $row['school'] = ClassData::where([
                'id' => $row['class_data_id']
            ])->first()->name ?? '-';
            $row['class'] = YearClass::where([
                'id' => $row['year_class_id']
            ])->first()->name ?? '-';
            $row['grade'] = Grade::where([
                'id' => $row['grade_id']
            ])->first()->name ?? '-';
            $row['count'] = Student::where([
                'year_class_id' => $row['year_class_id']
            ])->count();
            $row['status_name'] = Common::planStatusArr()[$row['status']];

            if (strtotime($row['plan_date']) < time()) {
                $row['status_name'] = '超时未执行';
            }
        }

        return $this->successResponse([
            'count' => $data,
            'rows' => $rows
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:plans',
            'name'    => 'required|max:100',
            'plan_date' => 'required',
            'plan_user' => 'nullable|max:64',
            'remark' => 'nullable|max:200',
            'grade_id'  => 'required|exists:grades,id',
            'class_id' => 'required|exists:year_classes,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

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

        $res = Plan::where([
            'id' => $request->input('id')
        ])->update([
            'name' => $request->input('name'),
            'plan_date' => $request->input('plan_date'),
            'plan_user' => $request->input('plan_user'),
            'remark' => $request->input('remark'),
            'grade_id' => $request->input('grade_id'),
            'year_class_id' => $request->input('class_id'),
            'class_data_id' => $classInfo['class_data_id']
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:plans,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = Plan::where([
            'id' => $request->input('id')
        ])->update([
            'is_del' => Common::YES
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:plans,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $data = Plan::where([
            'id' => $request->input('id'),
//            'is_del'    => Common::NO
        ])->first();

        $data['grade'] = Grade::where([
            'id' => $data['grade_id']
        ])->first()->name;
        $data['class'] = YearClass::where([
            'id' => $data['year_class_id']
        ])->first()->name;
        $student = Student::where([
            'year_class_id' => $data['year_class_id']
        ])->get();
        $data['count'] = $student->count();
        $data['studentIdArr'] = $student->pluck('id');
        $data['status_name'] = Common::planStatusArr()[$data['status']];

        if (strtotime($data['plan_data']) < time()) {
            $data['status_name'] = '超时未验光';
        }

        return $this->successResponse($data);
    }

    public function done(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:plans,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        // 检查是否有学生尚未检测完毕
        $exists = Student::where([
            'plan_id' => $request->input('id'),
            'plan_status' => 1
        ])->count();

        if ($exists) {
            return $this->errorResponse('系统检测到还有' . $exists . '个学生');
        }

        $res = Plan::whereId($request->input('id'))->update([
            'status' => 2
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
