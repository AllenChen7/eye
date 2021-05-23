<?php

namespace App\Http\Controllers\Api;

use App\Exports\StudentExport;
use App\Http\Controllers\ApiController;
use App\Imports\StudentImport;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentLog;
use App\Models\YearClass;
use App\ModelsData\StudentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentController extends ApiController
{
    public function create(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'                  => 'required|max:50',
            'birthday'              => 'required|date',
            'student_code'          => 'required|max:32',
            'id_card'               => 'required|max:50',
            'sex'                   => [
                'required', Rule::in(Common::sexArrKeys()),
            ],
            'grade_id'              => 'required|exists:grades,id',
            'class_id'              => 'required|exists:year_classes,id',
            'is_myopia'             => [
                'required', Rule::in(array_flip(Common::isMyopiaArr())),
            ],
            'is_glasses'            => [
                'required', Rule::in(Common::isKeys())
            ],
            'join_school_date'      => 'required'
        ]);

        $validator->sometimes('glasses_type', 'required', function ($input) {
            return $input->is_glasses;
        });

        $validator->sometimes('l_degree', 'required', function ($input) {
            return $input->is_myopia == Common::NO || $input->is_myopia == Common::OTHER;
        });

        $validator->sometimes('r_degree', 'required', function ($input) {
            return $input->is_myopia == Common::NO || $input->is_myopia == Common::OTHER;
        });

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        if (!isDate($request->input('join_school_date') . '-01-01', 'Y-m-d')) {
            return $this->errorResponse('入学年份格式不正确');
        }

        // 先查处 id card 对应数据是否存在
        $oldData = StudentData::studentByIdCard($request->input('id_card'));

        if ($oldData) {

            if ($oldData['student_code'] != $request->input('student_code')) {
                $exCode = Student::where([
                    'student_code' => $request->input('student_code'),
                    'class_data_id' => auth()->user()->class_data_id
                ])->first();

                if ($exCode) {
                    return $this->errorResponse('学号在此校重复！');
                }
            }

            // 班级发生改变时，验光计划恢复初始化
            if ($oldData->class_data_id != auth()->user()->class_date_id ||
                $oldData->grade_id != $request->input('grade_id') ||
                $oldData->year_class_id != $request->input('class_id')) {
                $oldData->plan_id = 0;
                $oldData->plan_status = 0;
            }

            $oldData->class_data_id = auth()->user()->class_data_id;
            $oldData->grade_id = $request->input('grade_id');
            $oldData->year_class_id = $request->input('class_id');
            $oldData->is_myopia = $request->input('is_myopia');
            $oldData->is_glasses = $request->input('is_glasses');
            $oldData->glasses_type = (int)$request->input('glasses_type');
            $oldData->l_degree = (int)$request->input('l_degree');
            $oldData->r_degree = (int)$request->input('r_degree');
            $oldData->join_school_date = $request->input('join_school_date');

            if ($oldData->save()) {
                return $this->successResponse();
            } else {
                return $this->errorResponse();
            }
            // 否则进行更新到本次提交数据
        } else {
            $exCode = Student::where([
                'student_code' => $request->input('student_code'),
                'class_data_id' => auth()->user()->class_data_id
            ])->first();

            if ($exCode) {
                return $this->errorResponse('学号在此校重复！');
            }

            $model = new Student($request->input());
            $model->create_user_id = auth()->id();
            $model->class_data_id = auth()->user()->class_data_id;
            $model->grade_id = $request->input('grade_id');
            $model->year_class_id = $request->input('class_id');
            $model->join_school_date = $request->input('join_school_date');
            $model->l_sph = (int)$request->input('l_sph');
            $model->r_sph = (int)$request->input('r_sph');
            $model->l_degree = (int)$request->input('l_degree');
            $model->r_degree = (int)$request->input('r_degree');

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
        $model->sex = $request->input('sex', '');
        $model->joinDate = $request->input('date');

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

        $planId = $request->input('plan_id', 0);

        if ($planId) {
            $data = StudentLog::where([
                'student_id' => $request->input('id'),
                'plan_id'   => $planId
            ])->orderByDesc('id')->first();
        } else {
            $data = Student::where([
                'id' => $request->input('id')
            ])->first();
        }

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
        $data['PD'] = $data['pd'];
        $data['age'] = Common::transYearOld($data['birthday']);

        return $this->successResponse($data);
    }

    public function update(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'id'                    => 'required|exists:students',
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
            ],
            'join_school_date'      => 'required'
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

        if (!isDate($request->input('join_school_date') . '-01-01', 'Y-m-d')) {
            return $this->errorResponse('入学年份格式不正确');
        }

        // 先查处 id card 对应数据是否存在
        $oldData = Student::whereId($request->input('id'))->first();

        $oldData->class_data_id = auth()->user()->class_data_id;
        $oldData->name = $request->input('name');
        $oldData->birthday = $request->input('birthday');
        $oldData->student_code = $request->input('student_code');
        $oldData->id_card = $request->input('id_card');
        $oldData->sex = $request->input('sex');
        $oldData->grade_id = $request->input('grade_id');
        $oldData->year_class_id = $request->input('class_id');
        $oldData->is_myopia = $request->input('is_myopia');
        $oldData->is_glasses = $request->input('is_glasses');
        $oldData->glasses_type = (int)$request->input('glasses_type');
        $oldData->l_degree = (int)$request->input('l_degree');
        $oldData->r_degree = (int)$request->input('r_degree');
        $oldData->join_school_date = $request->input('join_school_date');

        if ($oldData->save()) {
            return $this->successResponse();
        } else {
            return $this->errorResponse();
        }
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

        Log::alert('input', $request->input());
        $left = $request->input('left');
        $right = $request->input('right');
        $student = Student::whereId([
            'id' => $request->input('id')
        ])->first();

        $studentLogModel = StudentLog::where([
            'student_id' => $request->input('id'),
            'plan_id'   => $student->plan_id
        ])->first();

        if (!$studentLogModel) {
            $studentLogModel = new StudentLog();
            $studentLogModel->student_id = $request->input('id');
            $studentLogModel->name = $student['name'];
            $studentLogModel->sex = $student->sex;
            $studentLogModel->student_code = $student->student_code;
            $studentLogModel->id_card = $student->id_card;
            $studentLogModel->birthday = $student->birthday;
            $studentLogModel->class_data_id = $student->class_data_id;
            $studentLogModel->grade_id = $student->grade_id;
            $studentLogModel->year_class_id = $student->year_class_id;
            $studentLogModel->create_user_id = auth()->id();
            $studentLogModel->is_myopia = $student->is_myopia;
            $studentLogModel->is_glasses = $student->is_glasses;
            $studentLogModel->glasses_type = $student->glasses_type;
            $studentLogModel->status = $student->status;
            $studentLogModel->join_school_date = $student->join_school_date;
            $studentLogModel->is_del = $student->is_del;
            $studentLogModel->plan_id = $student->plan_id;
        }

        $studentLogModel->l_degree = $left['sph'] ?? 0;
        $studentLogModel->l_sph = $left['sph'] ?? 0;
        $studentLogModel->l_cyl = $left['cyl'] ?? 0;
        $studentLogModel->l_axi = $left['axi'] ?? 0;
        $studentLogModel->l_roc1 = $left['roc1'] ?? 0;
        $studentLogModel->l_roc2 = $left['roc2'] ?? 0;
        $studentLogModel->l_axis = $left['axis'] ?? 0;
        $studentLogModel->r_degree = $right['sph'] ?? 0;
        $studentLogModel->r_sph = $right['sph'] ?? 0;
        $studentLogModel->r_cyl = $right['cyl'] ?? 0;
        $studentLogModel->r_axi = $right['axi'] ?? 0;
        $studentLogModel->r_roc1 = $right['roc1'] ?? 0;
        $studentLogModel->r_roc2 = $right['roc2'] ?? 0;
        $studentLogModel->r_axis = $right['axis'] ?? 0;
        $studentLogModel->pd = $left['PD'] ?? 0;
        $studentLogModel->plan_date = date('Y');

        if (!$studentLogModel->save()) {
            Log::error('save error', $studentLogModel);
            return $this->errorResponse();
        }

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
            'plan_status'   => 2,
            'pd'    => $left['PD'] ?? 0,
            'plan_date' =>  date('Y')
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function import(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'excel' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:10485760'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $excel = $request->file('excel');
        $studentImport = new StudentImport();
        $studentImport->cacheStr = 'student_import_' . auth()->user()->class_data_id;
        Excel::import($studentImport, $excel);
        $excelArr = [];

        if ($studentImport->errorFlag == -1) {
            return $this->errorResponse($studentImport->cacheStr);
        }

        if ($studentImport->successFlag) {
            $data = array_merge([StudentData::excelTitle()], $studentImport->successCacheData);
            $columnArr = [
                'B' => NumberFormat::FORMAT_TEXT
            ];
            $fileName = '批量添加成功学生信息' . date('Y-m-d-H-i-s') . '.xlsx';
            $sRes = (new StudentExport($data, $columnArr))->store($fileName, 'public');

            if ($sRes) {
                $excelArr[] = [
                    'name' => $fileName,
                    'status' => 1,
                    'status_name' => '导入成功',
                    'create_time' => date('Y-m-d H:i:s'),
                    'url' => asset('storage/' . $fileName),
                ];
            } else {
                return $this->errorResponse();
            }
        }

        if ($studentImport->errorFlag) {
            $title = StudentData::excelTitle();
            $title[] = '失败备注';
            $data = array_merge([$title], $studentImport->cacheData);
            $columnArr = [
                'B' => NumberFormat::FORMAT_TEXT
            ];
            $fileName = '批量添加失败学生信息' . date('Y-m-d-H-i-s') . '.xlsx';
            $sRes = (new StudentExport($data, $columnArr))->store($fileName, 'public');

            if ($sRes) {
                $excelArr[] = [
                    'name' => $fileName,
                    'status' => 0,
                    'status_name' => '导入失败',
                    'create_time' => date('Y-m-d H:i:s'),
                    'url' => asset('storage/' . $fileName),
                ];
            } else {
                return $this->errorResponse();
            }
        }

        return $this->successResponse($excelArr);
    }

    public function classStudent(Request $request)
    {
        if (!auth()->user()->class_data_id) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'grade_id'              => 'required|exists:grades,id',
            'class_id'              => 'required|exists:year_classes,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $classInfo = YearClass::whereId($request->input('class_id'))->first();

        if ($classInfo->class_data_id != auth()->user()->class_data_id) {
            return $this->errorResponse('没有操作当前班级的权限');
        }

        if ($classInfo->grade_id != $request->input('grade_id')) {
            return $this->errorResponse('年级班级不匹配');
        }

        $gradeInfo = Grade::whereId($request->input('grade_id'))->first();
        $student = Student::where([
            'year_class_id' => $request->input('class_id')
        ])->select([
            'id', 'name', 'sex', 'id_card', 'student_code'
        ])->get();

        foreach ($student as &$s) {
            $s['sex_name'] = Common::sexArr()[$s['sex']];
            $s['grade_name'] = $gradeInfo['name'];
            $s['class_name'] = $classInfo['name'];
        }

        $count = $student->count();

        return $this->successResponse([
            'count' => $count,
            'rows' => $student
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'              => 'required|exists:students,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $idArr = (new ClassData())->idArr();
        $studentInfo = Student::whereId($request->input('id'))->first();

        if (!in_array($studentInfo['class_data_id'], $idArr)) {
            return $this->errorResponse('不能删除当前学生信息');
        }

        $res = $studentInfo->delete();

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
