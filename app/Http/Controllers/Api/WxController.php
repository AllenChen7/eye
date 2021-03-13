<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\WxUser;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'encryptedData'                    => 'required',
            'iv'    => 'required'
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
}
