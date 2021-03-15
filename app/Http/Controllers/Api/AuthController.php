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
            'name'                  => 'required|max:64|unique:users',
            'phone'                 => 'required|unique:users|max:32',
            'password'              => 'required|confirmed|max:50',
            'password_confirmation' => 'required|same:password|max:50',
            'remark'                => 'nullable|max:200',
            'type'                  => [
                'required', Rule::in(Common::typeArrKeys()),
            ]
        ]);
        // 如果是校级的话需要指定省级-市级-县级并创建学校数据

        $validator->sometimes('province_id', 'required|exists:users,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_CITY, Common::TYPE_SCH, Common::TYPE_AREA
            ]);
        });

        $validator->sometimes('city_id', 'required|exists:users,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_SCH, Common::TYPE_AREA
            ]);
        });

        $validator->sometimes('area_id', 'required|exists:users,id', function ($input) {
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
        $model->power_user_id = \auth()->id(); // todo 结合用户管理综合处理一下
        $model->power_type  = \auth()->user()->type;

        switch ($request->input('type')) {
            case Common::TYPE_CITY:
                $model->province_id = $request->input('province_id');
                break;
            case Common::TYPE_AREA:
                $cityInfo = User::whereId($request->input('city_id'))->first();

                if ($cityInfo['province_id'] != $request->input('province_id')) {
                    return $this->errorResponse('市级数据与省级数据不匹配');
                }

                $model->province_id = $request->input('province_id');
                $model->city_id     = $request->input('city_id');
                break;
            case Common::TYPE_SCH:
                $areaInfo = User::whereId($request->input('area_id'))->first();

                if ($areaInfo['province_id'] != $request->input('province_id')) {
                    return $this->errorResponse('县级数据与省级数据不匹配');
                }

                if ($areaInfo['city_id'] != $request->input('city_id')) {
                    return $this->errorResponse('县级数据与市级数据不匹配');
                }
                $model->province_id = $request->input('province_id');
                $model->city_id     = $request->input('city_id');
                $model->area_id     = $request->input('area_id');

                if (Common::TYPE_SCH) {
                    // 先处理学校数据
                    $classData = ClassData::firstOrCreate([
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'area_id'       => $request->input('area_id'),
                        'name'          => $request->input('name')
                    ], [
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'area_id'       => $request->input('area_id'),
                        'name'          => $request->input('name'),
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

    /**
     * 省用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function province()
    {
        $res = User::where([
            'is_del' => Common::NO,
            'type'   => Common::TYPE_PROV
        ])->select(['id', 'name'])->get()->toArray();

        return $this->successResponse($res);
    }

    /**
     * 市用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function city(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::where([
            'type' => Common::TYPE_CITY,
            'province_id' => $request->input('id')
        ])->select(['id', 'name'])->get()->toArray();

        return $this->successResponse($res);
    }

    /**
     * 县级用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function area(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::where([
            'type' => Common::TYPE_AREA,
            'city_id' => $request->input('id')
        ])->select(['id', 'name'])->get()->toArray();

        return $this->successResponse($res);
    }
}
