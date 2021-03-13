<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Student;
use App\Models\YearClass;
use App\ModelsData\School;
use App\ModelsData\StudentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends ApiController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|max:50',
            'birthday'              => 'required|date',
            'student_code'          => 'required|max:50',
            'id_card'               => 'required|max:50',
            'sex'                   => [
                'required', Rule::in(Common::sexArrKeys()),
            ],
            'grade_id'              => 'required|exists:grades,id',
            'class_id'              => 'required|exists:year_classes,id',
            'is_myopia'             => [
                'required', Rule::in(Common::isKeys()),
            ],
            'is_glasses'            => [
                'required', Rule::in(Common::isKeys())
            ]
        ]);

        $validator->sometimes('glasses_type', 'required', function ($input) {
            return $input->is_glasses;
        });

        $validator->sometimes('l_degree', 'required', function ($input) {
            return $input->is_glasses;
        });

        $validator->sometimes('r_degree', 'required', function ($input) {
            return $input->is_glasses;
        });

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        // 先查处 id card 对应数据是否存在
        $oldData = StudentData::studentByIdCard($request->input('id_card'));

        if ($oldData) {
            // 如果 学校、年级、班级一致的话则提示已存在
            if ($oldData['class_data_id'] == auth()->user()->class_data_id &&
                $oldData['grader_id'] == $request->input('grader_id') &&
                $oldData['year_class_id'] == $request->input('class_id')) {
                return $this->errorResponse('数据已存在，不能再次添加');
            } else {
                $oldData->class_data_id = auth()->user()->class_data_id;
                $oldData->grader_id = $request->input('grade_id');
                $oldData->year_class_id = $request->input('class_id');
                $oldData->is_myopia = $request->input('is_myopia');
                $oldData->is_glasses = $request->input('is_glasses');
                $oldData->glasses_type = $request->input('glasses_type');
                $oldData->l_degree = $request->input('l_degree');
                $oldData->r_degree = $request->input('r_degree');

                if ($oldData->save()) {
                    return $this->successResponse();
                } else {
                    return $this->errorResponse();
                }
            }
            // 否则进行更新到本次提交数据
        } else {
            $model = new Student($request->input());
            $model->create_user_id = auth()->id();
            $model->class_data_id = auth()->user()->class_data_id;
            $model->grader_id = $request->input('grade_id');
            $model->year_class_id = $request->input('class_id');

            if ($model->save()) {
                return $this->successResponse();
            } else {
                return $this->errorResponse();
            }
        }
    }

    public function list(Request $request)
    {
        $model = new StudentData();
        $model->schoolId = $request->input('school_id');
        $model->idCard = $request->input('id_card');
        $model->gradeId = $request->input('grade_id');
        $model->classId = $request->input('class_id');
        $model->studentCode = $request->input('student_code');
        $model->name = $request->input('name');
        $model->limit = $request->input('limit', $model->limit);
        $model->page = $request->input('page', $model->page);

        $count = $model->rowCount();
        $rows = $model->rowData();

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:students,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $data = Student::where([
            'id' => $request->input('id')
        ])->first();

        $data['image'] = Common::transPhoto($data['sex']);
        $data['grade'] = Grade::where([
            'id' => $data['grade_id']
        ])->first()->name ?? '-';
        $data['class'] = YearClass::where([
            'id' => $data['year_class_id']
        ])->first()->name ?? '--';
        $data['sex_name'] = Common::sexArr()[$data['sex']];
        $data['is_myopia_name'] = Common::isArr()[$data['is_myopia']];
        $data['is_glasses_name'] = Common::isArr()[$data['is_glasses']];
        $data['glasses_type_name'] = Common::glaType()[$data['glasses_type']];

        return $this->successResponse($data);
    }

    /**
     * 度数更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDegree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:students,id',
            'left' => 'required',
            'right' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $left = $request->input('left');
        $right = $request->input('right');

        $res = Student::where([
            'id' => $request->input('id')
        ])->update([
            'l_degree' => $left['sph'] ?? 0,
            'l_sph' => $left['sph'] ?? 0,
            'l_cyl' => $left['cyl'] ?? 0,
            'l_axi' => $left['axi'] ?? 0,
            'l_roc1' => $left['roc1'] ?? 0,
            'l_roc2' => $left['roc2'] ?? 0,
            'l_axis' => $left['axis'] ?? 0,
            'r_degree' => $right['sph'] ?? 0,
            'r_sph' => $right['sph'] ?? 0,
            'r_cyl' => $right['cyl'] ?? 0,
            'r_axi' => $right['axi'] ?? 0,
            'r_roc1' => $right['roc1'] ?? 0,
            'r_roc2' => $right['roc2'] ?? 0,
            'r_axis' => $right['axis'] ?? 0,
        ]);

        if ($res) {
            // todo 快照数据
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
