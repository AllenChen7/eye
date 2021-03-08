<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\ModelsData\Areas;
use App\ModelsData\UsersData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    /**
     * 通过手机号密码登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginByPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $credentials = $request->only('phone', 'password');
        $token = $this->guard()->attempt($credentials);

        if ($token) {
            return $this->respondWithToken($token);
        }

        return $this->errorResponse();
    }

    /**
     * 通过手机号密码登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $token = $this->guard()->attempt($credentials);

        if ($token) {
            return $this->respondWithToken($token);
        }

        return $this->errorResponse();
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|max:50',
            'phone'                 => 'required|unique:users|max:20',
            'password'              => 'required|confirmed|max:50',
            'password_confirmation' => 'required|same:password|max:50',
            'remark'                => 'nullable|max:200',
            'type'                  => [
                'required', Rule::in(Common::typeArrKeys()),
            ]
        ]);

        $validator->sometimes('province_id', 'required|exists:province_city_area,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_PROV, Common::TYPE_CITY, Common::TYPE_SCH
            ]);
        });

        $validator->sometimes('city_id', 'required|exists:province_city_area,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_CITY, Common::TYPE_SCH
            ]);
        });

        $validator->sometimes('school_name', 'required', function ($input) {
            return in_array($input->type, [
                Common::TYPE_SCH
            ]);
        });

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $model              = new User();
        $model->name        = $request->input('name');
        $model->phone       = $request->input('phone');
        $model->password    = \Hash::make($request->input('password'));
        $model->remark      = $request->input('remark');
        $model->type        = $request->input('type');
        $model->create_user_id = \auth()->id();

        switch ($request->input('type')) {
            case Common::TYPE_PROV:
                $model->province_id = $request->input('province_id');
                break;
            case Common::TYPE_CITY:
            case Common::TYPE_SCH:
                $model->province_id = $request->input('province_id');
                $model->city_id     = $request->input('city_id');

                $res = Areas::areasDataByCityIdAndProvinceId($request->input('city_id'), $request->input('province_id'));

                if (!$res) {
                    $data = [
                        'city_id' => [
                            '省市数据不匹配'
                        ]
                    ];

                    return $this->errorResponse('验证错误', $data, 422);
                }

                if (Common::TYPE_SCH) {
                    // 先处理学校数据
                    $classData = ClassData::firstOrCreate([
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'name'          => $request->input('school_name')
                    ], [
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'name'          => $request->input('school_name'),
                        'create_user_id'=> \auth()->id()
                    ]);

                    $model->class_data_id = $classData->id;
                }
                break;
        }

        if ($model->save()) {
            return $this->successResponse();
        } else {
            return $this->errorResponse();
        }
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->successResponse($this->guard()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $this->guard()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->successResponse([], '退出成功');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
