<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use App\Models\Student;
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
}
