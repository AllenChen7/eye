<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\YearClass;
use App\ModelsData\School;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolController extends ApiController
{
    public function create(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

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
                    'updated_at' => Carbon::now(),
                    'class_data_id' => auth()->user()->class_data_id
                ];
            }

            $res = \DB::table('year_classes')->insert($insertData);

            if ($res) {
                return $this->successResponse();
            }
        }

        return $this->errorResponse();
    }

    public function addClass(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:grades',
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

        $exists = School::yearClassByName($request->input('class'));

        if ($exists->isNotEmpty()) {
            $data = [
                'class' => [
                    '班级名称不能重复'
                ]
            ];

            return $this->errorResponse('验证错误', $data, 422);
        }

        $insertData = [];

        foreach ($request->input('class') as $class) {
            $class = trim($class);
            $insertData[] = [
                'grade_id' => $request->input('id'),
                'name' => $class,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $res = \DB::table('year_classes')->insert($insertData);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $type = $user['type'];
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

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

        $name = trim($request->input('name'));
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $count = $query->count();
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $rows = $query->limit($limit)->offset($offset)->get();

        foreach ($rows as &$row) {
            $row['grades'] = Grade::where([
                'class_data_id' => $row['id']
            ])->count();
            $row['classes'] = YearClass::where([
                'class_data_id' => $row['id']
            ])->count();
            $row['province'] = User::where([
                'id' => $row['province_id']
            ])->first()->name;
            $row['city'] = User::where([
                'id' => $row['city_id']
            ])->first()->name;
            $row['area'] = User::where([
                'id' => $row['area_id']
            ])->first()->name;
        }

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }

    public function grade()
    {
        $data = School::gradeListByClassDataId(auth()->user()->class_data_id);

        return $this->successResponse($data);
    }

    public function classData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:grades'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $data = School::classListByGradeId($request->input('id'));

        return $this->successResponse($data);
    }

    /**
     * 更新学校名称
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSchoolName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:class_data',
            'name'  => 'required|max:64'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        // 是否同步修改用户名称 todo
        $res = ClassData::where([
            'id' => $request->input('id')
        ])->update([
            'name' => $request->input('name')
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
