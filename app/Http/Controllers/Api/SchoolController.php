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
    /**
     * 创建年级班级
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $model->create_user_id = auth()->id();

        if ($model->save()) {
            $insertData = [];

            foreach ($request->input('class') as $class) {
                $class = trim($class);
                $insertData[] = [
                    'grade_id' => $model->id,
                    'name' => $class,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'class_data_id' => auth()->user()->class_data_id,
                    'create_user_id' => auth()->id()
                ];
            }

            $res = \DB::table('year_classes')->insert($insertData);

            if ($res) {
                return $this->successResponse();
            }
        }

        return $this->errorResponse();
    }

    public function schoolList()
    {
        $idArr = (new ClassData())->idArr();
        $rows = ClassData::whereIn('id', $idArr)->select(['id', 'name'])->get();

        return $this->successResponse($rows);
    }

    /**
     * 添加班级
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $model = new YearClass();
            $model->grade_id = $request->input('id');
            $model->name = $class;
            $model->class_data_id = auth()->user()->class_data_id;

            if ($model->save()) {
                $insertData[] = $model;
            } else {
                return $this->errorResponse();
            }
        }

        if ($insertData) {
            return $this->successResponse($insertData);
        }

        return $this->errorResponse();
    }

    /**
     * 学校列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $query = ClassData::orderByDesc('id');
        $idArr = (new ClassData())->idArr();
        $query->whereIn('id', $idArr);

        $name = trim($request->input('name'));

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $count = $query->count();
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $rows = $query->limit($limit)->offset($offset)->get();

        foreach ($rows as &$row) {
            $row['status_name'] = Common::statusArr()[$row['status']];
            $row['grades'] = Grade::where([
                'class_data_id' => $row['id'],
                'status' => Common::YES
            ])->count();
            $row['classes'] = YearClass::where([
                'class_data_id' => $row['id'],
                'status' => Common::YES
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

    public function grade(Request $request)
    {
        $schoolId = $request->input('school_id', '');

        if (!$schoolId) {
            $schoolId = auth()->user()->class_data_id;
        }

        $idArr = (new ClassData())->idArr();

        if (!in_array($schoolId, $idArr)) {
            return $this->errorResponse('没有操作当前数据的权限', [], 403);
        }

        $data = School::gradeListByClassDataId($schoolId);

        return $this->successResponse($data);
    }

    /**
     * 年级列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gradeList(Request $request)
    {
        $gradeId = $request->input('id', 0);
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

        if (!$gradeId) {
            $gradeId = auth()->user()->class_data_id;
        }

        if (!$gradeId) {
            return $this->successResponse([
                'count' => 0,
                'rows' => []
            ]);
        }

        $idArr = (new ClassData())->idArr();

        if (!in_array($gradeId, $idArr)) {
            return $this->errorResponse('没有操作当前数据的权限', [], 403);
        }

        $query = Grade::where([
            'class_data_id' => $gradeId,
            'status' => Common::YES
        ])->orderByDesc('id');

        $count = $query->count();
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $rows = $query->offset($offset)->limit($limit)->get();

        foreach ($rows as &$row) {
            $classInfo = YearClass::where([
                'grade_id' => $row['id'],
                'status' => Common::YES
            ])->get();

            $row['count'] = $classInfo->count();
            $row['class'] = $classInfo->toArray();
            $row['status_name'] = Common::statusArr()[$row['status']];
            $row['create_user_name'] = User::whereId($row['create_user_id'])->first()->name ?? '-';
        }

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }

    /**
     * 年级下班级数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        $idArr = (new ClassData())->idArr();

        if (!in_array($request->input('id'), $idArr)) {
            return $this->errorResponse('没有操作当前数据的权限', [], 403);
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

    /**
     * 删除年级
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGrade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:grades',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = Grade::whereId($request->input('id'))->update([
            'status' => Common::STATUS_DISABLED
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * 删除班级
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:year_classes',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = YearClass::whereId($request->input('id'))->update([
            'status' => Common::STATUS_DISABLED
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * 禁用学校
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSchool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:class_data',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $idArr = (new ClassData())->idArr();

        if (!in_array($request->input('id'), $idArr)) {
            return $this->errorResponse('没有操作当前数据的权限', [], 403);
        }

        $res = ClassData::where([
            'id' => $request->input('id')
        ])->update([
            'status' => Common::STATUS_DISABLED
        ]);

        if ($res) {
            return $this->successResponse();
        }
        // todo 将账号改为 无效

        return $this->errorResponse();
    }

    /**
     * 查看班级
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewGrade(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return $this->errorResponse('请先选中需要查看的学校');
        }

        $idArr = (new ClassData())->idArr();

        if (!in_array($id, $idArr)) {
            return $this->errorResponse('没有操作当前数据的权限', [], 403);
        }

        $schoolInfo = ClassData::whereId($id)->first();

        if (!$schoolInfo) {
            return $this->errorResponse('学校不存在');
        }

        $gradeInfo = Grade::where([
            'class_data_id' => $id,
            'status' => Common::YES
        ])->get();

        foreach ($gradeInfo as $grade) {
            $grade['created_at'] = date('Y-m-d H:i:s', strtotime($grade['created_at']));
            $grade['classInfo'] = YearClass::where([
                'grade_id' => $grade['id'],
                'status' => Common::YES
            ])->get()->toArray();
            $grade['school_id'] = $id;
        }

        return $this->successResponse([
            'school_name' => $schoolInfo['name'],
            'grade_info' => $gradeInfo
        ]);
    }

    public function updateGradeName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:grades',
            'name'  => 'required|max:64'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = Grade::whereId($request->input('id'))->update([
            'name' => $request->input('name')
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
