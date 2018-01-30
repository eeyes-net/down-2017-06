<?php

namespace app\auth\controller;

use phpCAS;
use think\Controller;
use think\Request;
use think\Env;

class CasLogin extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        init_php_cas();
    }

    /**
     * CAS登录
     *
     * @return \think\response\Redirect
     */
    public static function login()
    {
        phpCAS::forceAuthentication();
        return redirect(url('index/Index/index'));
    }

    /**
     * CAS注销
     */
    public function logout()
    {
        phpCAS::logoutWithRedirectService(request()->domain() . '/');
    }

    public function getUser()
    {
        if(phpCAS::isAuthenticated())
        {
            $username = phpCAS::getUser();
        } else {
            $username = '游客';
        }

        return json([
            'code' => '200',
            'username' => $username,
            'msg' => 'OK',
        ]);
    }
}
