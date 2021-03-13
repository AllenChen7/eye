<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use App\Models\Student;
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
}
