<?php

namespace App\Imports;

use App\Models\Common;
use App\Models\Grade;
use App\Models\Student;
use App\Models\YearClass;
use App\ModelsData\StudentData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Ofcold\IdentityCard\IdentityCard;

class StudentImport implements ToCollection
{
    public $cacheStr;
    public $errorFlag = 0;
    public $cacheData = [];
    public $successFlag = 0;
    public $successCacheData = [];
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        if (!$collection->toArray()) {
            $this->errorFlag = -1;
            $this->cacheStr = '导入数据为空';

            return false;
        }

        if ($collection->count() < 2) {
            $this->errorFlag = -1;
            $this->cacheStr = '导入数据为空';

            return false;
        }

        foreach ($collection as $key => $row) {
            $row = $row->toArray();
            if ($key === 0) {
                $title = StudentData::excelTitle();
                $titleStr = implode('-', $title);
                $excelTitleStr = implode('-', $row);

                if ($titleStr != $excelTitleStr) {
                    return [
                        'code' => '1',
                        'msg' => '导入模版不正确'
                    ];
                }

                continue;
            }

            // array:13 [
            //  0 => "郭1"
            //  1 => "110101199003076018"
            //  2 => "男"
            //  3 => "2000-02-03"
            //  4 => 2000
            //  5 => 131360233
            //  6 => "一年级"
            //  7 => "一班"
            //  8 => "是"
            //  9 => "是"
            //  10 => "普通眼镜"
            //  11 => 500
            //  12 => 600
            //]
            $studentName = trim($row[0]);
            $idCard = trim($row[1]);
            $sex = trim($row[2]);
            $birthday = trim($row[3]);
            $joinSchoolDay = trim($row[4]);
            $studentCode = trim($row[5]);
            $grade = trim($row[6]);
            $class = trim($row[7]);
            $isMyopia = trim($row[8]);
            $isGlasses = trim($row[9]);
            $glassesType = trim($row[10]);
            $lDegree = trim($row[11]);
            $rDegree = trim($row[12]);

            if (!$studentName) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '姓名不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$idCard) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '身份证不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$sex) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '性别不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$birthday) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '出生年月日不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$joinSchoolDay) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '入学年份不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$studentCode) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '学号不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$grade) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '年级不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$class) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '班级不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!$isMyopia) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '是否近视不可为空';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if ($isMyopia == '是') {
                if (!$isGlasses) {
                    $row[1] = "\t" . $row[1];
                    $row[count($row) + 1] = '是否佩戴眼镜不可为空';
                    $this->cacheData[] = $row;
                    $this->errorFlag = 1;
                    continue;
                }

                if ($isGlasses == '是') {
                    if (!$glassesType) {
                        $row[1] = "\t" . $row[1];
                        $row[count($row) + 1] = '眼镜类型不可为空';
                        $this->cacheData[] = $row;
                        $this->errorFlag = 1;
                        continue;
                    }

                    if (!$lDegree) {
                        $row[1] = "\t" . $row[1];
                        $row[count($row) + 1] = '左眼度数不可为空';
                        $this->cacheData[] = $row;
                        $this->errorFlag = 1;
                        continue;
                    }

                    if (!$rDegree) {
                        $row[1] = "\t" . $row[1];
                        $row[count($row) + 1] = '右眼度数不可为空';
                        $this->cacheData[] = $row;
                        $this->errorFlag = 1;
                        continue;
                    }
                }
            }

            if (mb_strlen($studentName) > 12) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '姓名最多可填 12 个字符';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            $idCardInfo = IdentityCard::make($idCard);

            if ($idCardInfo === false) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '身份证格式不正确';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!in_array($sex, Common::sexArr())) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '性别填写错误，只能填“男”或“女”';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!isDate($birthday)) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '出生年月日格式不对，正确格式为：“yyyy-mm-dd”';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!isDate($joinSchoolDay . '-01-01')) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '入学年份格式不对，正确格式为：“yyyy”';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!is_numeric($studentCode)) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '学号格式错误，仅支持阿拉伯数字';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (mb_strlen($studentCode) > 32) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '学号最多可填32个数字';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            $gradeInfo = Grade::where([
                'name' => $grade,
                'class_data_id' => auth()->user()->class_data_id
            ])->first();

            if (!$gradeInfo) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '年级在系统中不存在，请填写系统中正确的年级名称（请到学校管理中查看）';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            $classInfo = YearClass::where([
                'name' => $class,
                'class_data_id' => auth()->user()->class_data_id
            ])->first();

            if (!$classInfo) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '班级在系统中不存在，请填写系统中正确的年级名称（请到学校管理中查看）';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if ($classInfo['grade_id'] != $gradeInfo['id']) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '班级和年级不匹配（请到学校管理中查看）';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if (!in_array($isGlasses, Common::isArr())) {
                $row[1] = "\t" . $row[1];
                $row[count($row) + 1] = '是否佩眼镜的格式填写错误，仅支持填写“是”或“否”。';
                $this->cacheData[] = $row;
                $this->errorFlag = 1;
                continue;
            }

            if ($isGlasses == '是') {
                if (!$glassesType) {
                    $row[1] = "\t" . $row[1];
                    $row[count($row) + 1] = '当是否佩眼镜选择为“是”时，眼镜类型不可为空，仅支持填写“普通眼镜”或“隐形眼镜”。';
                    $this->cacheData[] = $row;
                    $this->errorFlag = 1;
                    continue;
                }

                if (!in_array($glassesType, Common::glaType())) {
                    $row[1] = "\t" . $row[1];
                    $row[count($row) + 1] = '眼镜类型格式错误，仅支持填写“普通眼镜”或“隐形眼镜”。';
                    $this->cacheData[] = $row;
                    $this->errorFlag = 1;
                    continue;
                }
            }

            $res = Student::updateOrCreate([
                'name' => $studentName,
                'sex' => $sex == '男' ? 1 : 2,
                'student_code' => $studentCode,
                'id_card' => $idCard,
                'birthday' => $birthday,
                'class_data_id' => auth()->user()->class_data_id,
                'grade_id' => $gradeInfo['id'],
                'year_class_id' => $classInfo['id'],
                'create_user_id' => auth()->id(),
                'is_myopia' => $isMyopia == '是' ? 1 : 0,
                'is_glasses' => $isGlasses == '是' ? 1 : 0,
                'glasses_type' => $glassesType == '隐形眼镜' ? 1 : 0,
                'l_degree' => intval($lDegree),
                'r_degree' => intval($rDegree),
                'join_school_date' => $joinSchoolDay
            ], [
                'name' => $studentName,
                'sex' => $sex == '男' ? 1 : 2,
                'student_code' => $studentCode,
                'id_card' => $idCard,
                'birthday' => $birthday,
                'class_data_id' => auth()->user()->class_data_id,
                'grade_id' => $gradeInfo['id'],
                'year_class_id' => $classInfo['id'],
                'is_myopia' => $isMyopia == '是' ? 1 : 0,
                'is_glasses' => $isGlasses == '是' ? 1 : 0,
                'glasses_type' => $glassesType == '隐形眼镜' ? 1 : 0,
                'l_degree' => intval($lDegree),
                'r_degree' => intval($rDegree),
                'join_school_date' => $joinSchoolDay
            ]);

            if ($res) {
                $this->successFlag = 1;
                $row[1] = "\t" . $row[1];
                $this->successCacheData[] = $row;
            }
        }

        return true;
    }
}
