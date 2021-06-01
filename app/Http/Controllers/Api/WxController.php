<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentLog;
use App\Models\WxSearchLog;
use App\Models\WxUser;
use App\Models\YearClass;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ofcold\IdentityCard\IdentityCard;

class WxController extends ApiController
{
    /**
     * 小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function wxMiniLogin(Request $request)
    {
        $config = config('wxmini');
        $app = Factory::miniProgram($config);

        $validator = Validator::make($request->all(), [
            'code'                    => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $session = $app->auth->session($request->input('code'));

        if (isset($session['errcode'])) {
            return $this->errorResponse();
        }

        $user = WxUser::firstOrCreate([
            'openid' => $session['openid']
        ], [
            'openid' => $session['openid'],
            'session_key' => $session['session_key']
        ]);

        if ($user) {
            $user->session_key = $session['session_key'];

            if ($user->save()) {
                $token = $this->guard()->attempt([
                    'openid' => $session['openid'],
                    'password'  => 'password'
                ]);

                if ($token) {
                    return $this->successResponse([
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => $this->guard()->factory()->getTTL() * 60,
                        'user' => $this->guard()->user()
                    ]);
                }
            }
        }

        return $this->errorResponse();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('wx');
    }

    public function decryptData(Request $request)
    {
        $config = config('wxmini');
        $app = Factory::miniProgram($config);
        $validator = Validator::make($request->all(), [
            'encryptedData' => 'required',
            'iv'            => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $user = \auth('wx')->user();
        $decryptedData = $app->encryptor->decryptData(
            $user['session_key'], $request->input('iv'), $request->input('encryptedData')
        );

        if (isset($decryptedData['openId'])) {
            $res = WxUser::where([
                'openid' => $decryptedData['openId']
            ])->update([
                'nickname' => $decryptedData['nickName'],
                'gender' => $decryptedData['gender'],
                'language' => $decryptedData['language'],
                'city'  => $decryptedData['city'],
                'province' => $decryptedData['province'],
                'country' => $decryptedData['country'],
                'avatar' => $decryptedData['avatarUrl']
            ]);

            if ($res) {
                unset($decryptedData['watermark']);
                return $this->successResponse($decryptedData);
            }
        }

        return $this->errorResponse();
    }

    public function updateUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_info' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $decryptedData = $request->input('user_info');
        $user = \auth('wx')->user();
        $res = WxUser::where([
            'id' => $user['id']
        ])->update([
            'nickname' => $decryptedData['nickName'] ?? '-',
            'gender' => $decryptedData['gender'] ?? 0,
            'language' => $decryptedData['language'] ?? '-',
            'city'  => $decryptedData['city'] ?? '-',
            'province' => $decryptedData['province'] ?? '-',
            'country' => $decryptedData['country'] ?? '-',
            'avatar' => $decryptedData['avatarUrl'] ?? '',
//            'unionid' => $decryptedData['unionid'] ?? ''
        ]);

        if ($res) {
            return $this->successResponse($decryptedData);
        }

        return $this->errorResponse();
    }

    public function list(Request $request)
    {
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $name = $request->input('name');

        $query = WxUser::orderByDesc('id');
        $name = trim($name);

        if ($name) {
            $query->where('nickname', 'like', '%' . $name . '%');
        }

        $count = $query->limit($limit)->offset($offset)->count();
        $rows = $query->get();

        foreach ($rows as &$row) {
            $row['sex'] = Common::sexArr()[$row['gender']];
            $row['school_name'] = ClassData::where([
                'id' => $row['class_data_id']
            ])->first()->name ?? '-';
        }

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'id_card'    => 'required'
        ]);

//        \DB::table('wx_users')->where([
//            'id' => \auth('wx')->id()
//        ])->increment('nums');

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $idCard = trim($request->input('id_card'));
//        $idCardCheck = IdentityCard::make($idCard);
//
//        if (!$idCardCheck) {
//            return $this->errorResponse('请输入正确的身份证！');
//        }

        $name = trim($request->input('name'));
        $data = Student::where([
            'name' => $name,
            'id_card' => $idCard
        ])->first();

        if (!$data) {
            return $this->errorResponse('很抱歉，您查询的学生不存在');
        }

        $data['image'] = Common::transPhoto($data['sex']);
        $data['grade'] = Grade::where([
                'id' => $data['grade_id']
            ])->first()->name ?? '--';
        $data['class'] = YearClass::where([
                'id' => $data['year_class_id']
            ])->first()->name ?? '--';
        $data['sex_name'] = Common::sexArr()[$data['sex']];
        $data['is_myopia_name'] = Common::isMyopiaArr()[$data['is_myopia']];

        if ($data['is_myopia'] == Common::YES) {
            $data['is_glasses_name'] = '';
            $data['glasses_type_name'] = '';
            $data['is_glasses'] = '';
            $data['glasses_type'] = '';
        } else {
            $data['is_glasses_name'] = Common::isArr()[$data['is_glasses']];
            $data['glasses_type_name'] = Common::glaType()[$data['glasses_type']];
        }

        $data['PD'] = $data['pd'];
        $old = StudentLog::where([
            'student_id' => $data['id']
        ])->first();

        $data['old_l_degree'] = $old['l_degree'] ?? $data['l_degree'];
        $data['old_r_degree'] = $old['r_degree'] ?? $data['r_degree'];
        $data['school_name'] = ClassData::whereId($data['class_data_id'])->first()->name ?? '--';
        $data['age'] = Common::transYearOld($data['birthday']);

        // 记录查询数据
//        $searchModel = new WxSearchLog();
//        $searchModel->wx_user_id = \auth('wx')->id();
//        $searchModel->student_id = $data['id'];
//        $searchModel->l_degree = $data['l_degree'];
//        $searchModel->r_degree = $data['r_degree'];
//        $searchModel->class_data_id = $data['class_data_id'];
//        $searchModel->save();
//
//        WxUser::where([
//            'id' => \auth('wx')->id()
//        ])->update([
//            'class_data_id' => $data['class_data_id']
//        ]);

        return $this->successResponse($data);
    }
}
